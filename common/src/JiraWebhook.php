<?php
namespace jira;

use events\JiraEvent;
use events\JiraUseful;
use events\TimestampEvent;
use events\Usefull;
use jira\dto\ChangeLog;
use services\ChannelService;
use telegram\Button;
use logics\Log;
use telegram\TelegramApi;
use telegram\TelegramBot;


class JiraWebhook
{
    use TimestampEvent, JiraEvent, Usefull, JiraUseful;

    const ISSUE_MENU   = 'issue_menu';
    const FIELD_MENU   = 'field_menu';
    const COMMENT_MENU = 'comment_menu';
    const SPEAK_MENU   = 'speak_menu';
    const STATUS_MENU  = 'speak_menu';

    private $raw_data;

    private $data;

    private $data_export;

    private $webhookEvent;
    /**
     * @var ChangeLog[]
     */
    private $changeLog;

    /**
     * @var JiraApi
     */
    protected $api;

    /**
     * Jira constructor.
     * @throws \Exception
     */
    public function __construct(JiraApi $api)
    {
        $this->api = $api;
        $this->data = $this->api->getData();
        $this->raw_data = $this->api->getRawData();

        \utils\Log::prettyInfo($this->data, 'jira_get_log.txt');
    }

    public function getParsedData(): array
    {
        if (empty($this->data['issue']['key'])) {
            throw new \Exception('Empty key');
        }

        $issue = $this->api->getIssueAgile($this->data['issue']['key']);

        $data['key'] = @$issue['key'];

        $data['dat_start']       = @date('Y-m-d H:i:s', strtotime(@$issue['fields']['created']));
        $data['dat_last']        = @date('Y-m-d H:i:s', strtotime(@$issue['fields']['updated']));

        if (empty($issue['fields']['duedate'])) {
            $data['duedate'] = null;
        } else {
            $data['duedate'] = date('Y-m-d', strtotime($issue['fields']['duedate']));
        }

        $data['project']         = @$issue['fields']['project']['key'];
        $data['priority']        = @$issue['fields']['priority']['name']; //id

        $data['status_task']     = @$issue['fields']['status']['name']; //id
        $data['status_category'] = @$issue['fields']['status']['statusCategory']['name']; //name, id


        $data['to']              = $issue['fields']['assignee']['key'];
        $data['to_name']         = @$issue['fields']['assignee']['displayName'];

        $data['reporter']        = @$issue['fields']['reporter']['key'] ?? ''; //name, displayName
        $data['reporter_name']   = @$issue['fields']['reporter']['displayName'] ?? '';

        $data['author']          = $this->data['comment']['author']['key'] ?? $issue['fields']['creator']['key'] ?? ''; //name, displayName
        $data['author_name']     = $this->data['comment']['author']['displayName']  ?? $issue['fields']['creator']['displayName'] ?? '';

        $data['issue_type']      = @$issue['fields']['issuetype']['name'];
        $data['parent']          = @$issue['fields']['parent']['key'] ?? '';
        $data['summary']         = @$issue['fields']['summary'];

        $data['src'] = json_encode($issue);

        if(isset($this->data['changelog']) and (self::ISSUE_UPDATED == $this->data['webhookEvent'])){
            //foreach ($this->data['changelog']['items'] as $log) {
                //$changeLog = ChangeLog::fromArray($log);
                $changeLog = ChangeLog::fromArray($this->data['changelog']['items'][0]);

                if('assignee' == $changeLog->getFieldId()) {
                    if (empty($changeLog->getTo())) {
                        $changeLog->setWebhook(self::ASSIGNEE_DELETED);
                    } else {
                        $changeLog->setWebhook(self::ASSIGNEE_CREATED);
                    }
                } elseif ('components' == $changeLog->getFieldId()) {
                    if(empty($changeLog->getFrom())) {
                        $changeLog->setWebhook(self::COMPONENT_CREATED);
                    } elseif(empty($changeLog->getTo())) {
                        $changeLog->setWebhook(self::COMPONENT_DELETED);
                    } else {
                        $changeLog->setWebhook(self::COMPONENT_UPDATED);
                    }
                } elseif ('issuetype' == $changeLog->getFieldId()) {
                    $changeLog->setWebhook(self::ISSUE_TYPE_UPDATED);
                } elseif('fixVersions' == $changeLog->getFieldId()) {
                    if(empty($changeLog->getFrom())) {
                        $changeLog->setWebhook(self::FIXVERSION_CREATED);
                    } elseif(empty($changeLog->getTo())) {
                        $changeLog->setWebhook(self::FIXVERSION_DELETED);
                    } else {
                        $changeLog->setWebhook(self::FIXVERSION_UPDATED);
                    }
                } elseif(\CustomFieldGlobal::STORYPOINT == $changeLog->getFieldId()) {
                    if(empty($changeLog->getFromString())) {
                        $changeLog->setWebhook(self::STORY_POINT_CREATED);
                    } elseif(empty($changeLog->getToString())) {
                        $changeLog->setWebhook(self::STORY_POINT_DELETED);
                    } else {
                        $changeLog->setWebhook(self::STORY_POINT_UPDATED);
                    }
                } elseif('Sprint' == $changeLog->getField()) {
                    if (empty($changeLog->getFrom())) {
                        $changeLog->setWebhook(self::SPRINT_ATTACH);
                    } elseif (empty($changeLog->getTo())) {
                        $changeLog->setWebhook(self::SPRINT_DETACH);
                    } else {
                        $changeLog->setWebhook(self::SPRINT_REATTACH);
                    }
                } elseif ('description' == $changeLog->getField()) {
                    $changeLog->setWebhook(self::DESCRIPTION_UPDATED);
                } elseif('timeestimate' == $changeLog->getField()) {

                    if($changeLog->getFrom() < 1) {
                        $changeLog->setWebhook(self::ESTIMATE_CREATED);
                    } elseif($changeLog->getTo() < 1) {
                        $changeLog->setWebhook(self::ESTIMATE_DELETED);
                    } else {
                        $changeLog->setWebhook(self::ESTIMATE_UPDATED);
                    }

                } elseif('resolution' == $changeLog->getField()) {
                    if (strtolower($changeLog->getToString()) == 'done') {
                        $changeLog->setWebhook(self::ISSUE_DONE);
                    } elseif (empty($changeLog->getToString())) {
                        $changeLog = ChangeLog::fromArray($this->data['changelog']['items'][1]);
                        $changeLog->setWebhook(self::STATUS_UPDATED);
                    } else {
                        $changeLog->setWebhook($this->data['webhookEvent']);
                    }
                } elseif('reporter' == $changeLog->getFieldId()) {
                    $changeLog->setWebhook(self::REPORTER_CREATED);
                } elseif ('summary' == $changeLog->getFieldId()) {
                    if (empty($changeLog->getFromString())) {
                        $changeLog->setWebhook(self::SUMMARY_CREATED);
                    } else {
                        $changeLog->setWebhook(self::SUMMARY_UPDATED);
                    }
                } elseif('status' == $changeLog->getFieldId()) {
                    $changeLog->setWebhook(self::STATUS_UPDATED);
                } elseif('priority' == $changeLog->getField()) {
                    $changeLog->setWebhook(self::PRIORITY_UPDATED);
                } elseif('duedate' == $changeLog->getField()) {
                    if(empty($changeLog->getFrom())) {
                        $changeLog->setWebhook(self::DUEDATE_CREATED);
                    } elseif(empty($changeLog->getTo())) {
                        $changeLog->setWebhook(self::DUEDATE_DELETED);
                    } else {
                        $changeLog->setWebhook(self::DUEDATE_UPDATED);
                    }
                } elseif('labels' == $changeLog->getField()) {
                    if (strlen($changeLog->getFromString()) >= strlen($changeLog->getToString())) {
                        $changeLog->setWebhook(self::LABELS_DELETED);
                    } else {
                        $changeLog->setWebhook(self::LABELS_CREATED);
                    }
                } else {
                    $changeLog->setWebhook($this->data['webhookEvent']);
                }
                $changes[] = $changeLog;
          //  }
            $this->changeLog = $changes;
        } else{
            $changeLog = new ChangeLog();
            $changeLog->setWebhook( $this->data['webhookEvent'] );
            $this->changeLog = [$changeLog];
        }

        $data['action']  = $this->data['webhookEvent'];

        $data['status1'] = $this->data['webhookEvent'] == self::ISSUE_DELETED;
        
        $this->data_export = $data;
        return $data;
    }

    private function constructMessage(ChangeLog $change, array &$_msg)
    {
        $src         = json_decode($this->data_export['src'], true);
        $ikey        = $this->data['issue']['key'];
        $summary     = $this->data['issue']['fields']['summary'] ?? '';
        $status      = $src['fields']['status']['name'];
        $href        = $this->browseIssueLink($ikey);

        $_summary   = "[{$ikey}. {$summary}]({$href})";

        switch ($change->getWebhook()){
            case self::REPORTER_CREATED:
                $reporter = $change->getToString();
                $message = "Создатель задачи $_summary *{$reporter}*";
                break;
            case self::SUMMARY_CREATED:
                $summary = $change->getToString();
                $message = "В задаче $_summary добавлено название *{$summary}*";
                break;
            case self::SUMMARY_UPDATED:
                $summary = $change->getToString();
                $message = "В задаче $_summary обновлено название *{$summary}*";
                break;
            case self::COMPONENT_CREATED:
                $component = $change->getToString();
                $message = "В задаче $_summary добавлен компонент *{$component}*";
                break;
            case self::COMPONENT_UPDATED:
                $component = $change->getToString();
                $message = "В задаче $_summary обновлен компонент на *{$component}*";
                break;
            case self::COMPONENT_DELETED:
                $component = $change->getFromString();
                $message = "В задаче $_summary удален компонент *{$component}*";
                break;
            case self::FIXVERSION_CREATED:
                $fixversion = $change->getToString();
                $message = "В задаче $_summary добавлена версия фикса *{$fixversion}*";
                break;
            case self::FIXVERSION_UPDATED:
                $fixversion_old = $change->getFromString();
                $fixversion_new = $change->getToString();
                $message = "В задаче $_summary версия фикса сменилась с *{$fixversion_old}* на *{$fixversion_new}*";
                break;
            case self::FIXVERSION_DELETED:
                $fixversion = $change->getFromString();
                $message = "В задаче $_summary удалена версия фикса *{$fixversion}*";
                break;
            case self::STORY_POINT_CREATED:
                $story_point = $change->getToString();
                $message = "В задаче $_summary добавлена историческая точка *{$story_point}*";
                break;
            case self::STORY_POINT_UPDATED:
                $story_point_old = $change->getFromString();
                $story_point_new = $change->getToString();
                $message = "В задаче $_summary историческая точка сменилась с *{$story_point_old}* на *{$story_point_new}*";
                break;
            case self::STORY_POINT_DELETED:
                $story_point = $change->getFromString();
                $message = "В задаче $_summary удалена историческая точка *{$story_point}*";
                break;
            case self::SPRINT_ATTACH:
                $sprint = $change->getToString();
                $message = "Задача $_summary добавлена в спринт *{$sprint}*";
                break;
            case self::SPRINT_REATTACH:
                $sprint_old = $change->getFromString();
                $sprint_new = $change->getToString();
                $message = "Задача $_summary переместилась из спринта *{$sprint_old}* в спринт *{$sprint_new}*";
                break;
            case self::SPRINT_DETACH:
                $sprint = $change->getFromString();
                $message = "Задача $_summary удалена из спринта *{$sprint}*";
                break;
            case self::ESTIMATE_CREATED:
                $estimate = $this->convEstimateToDaysAndHours($change->getTo());
                $message = "В задаче $_summary добавлена исходная оценка *{$estimate}*";
                break;
            case self::ESTIMATE_UPDATED:
                $estimate = $this->convEstimateToDaysAndHours($change->getTo());
                $message = "В задаче $_summary изменена исходная оценка на *{$estimate}*";
                break;
            case self::ESTIMATE_DELETED:
                $estimate = $this->convEstimateToDaysAndHours($change->getFrom());
                $message = "В задаче $_summary удалена исходная оценка *{$estimate}*";
                break;
            case self::DUEDATE_CREATED:
                $duedate = date("d.m.y", strtotime($change->getTo()));
                $message = "В задаче $_summary добавлен срок исполнения на *{$duedate}*";
                break;
            case self::DUEDATE_UPDATED:
                $duedate_from = date("d.m.y", strtotime($change->getFrom()));
                $duedate_to   = date("d.m.y", strtotime($change->getTo()));
                $message = 'В задаче '.$_summary.' изменился срок исполнения с *'.$duedate_from.'* на *'.$duedate_to.'*';
                break;
            case self::DUEDATE_DELETED:
                $duedate = date("d.m.y", strtotime($change->getFrom()));
                $message = 'В задаче '.$_summary.' удален срок исполнения с *'.$duedate.'*';
                break;
            case self::ASSIGNEE_CREATED:
                $assignee = $change->getToString();
                $message = 'В задаче '.$_summary.' изменился исполнитель на *'.$assignee.'*';
                break;
            case self::ASSIGNEE_DELETED:
                $message = 'В задаче '.$_summary.' удален исполнитель *'.$change->getFromString().'*';
                break;
            case self::LABELS_CREATED:
                $array_from = explode(' ', $change->getFromString());
                $array_to = explode(' ', $change->getToString());
                $label = array_pop(array_diff($array_to, $array_from));
                $message = "К задаче $_summary добавлена метка *{$label}*";
                break;
            case self::LABELS_DELETED:
                $array_from = explode(' ', $change->getFromString());
                $array_to = explode(' ', $change->getToString());
                $label = array_pop(array_diff($array_from, $array_to));
                $message = "В задаче $_summary удалена метка *{$label}*";
                break;
            case self::PRIORITY_UPDATED:
                $_priority = $change->getToString();
                $priority =  ($_priority === 'Highest')? 'Блокирующий' :
                            (($_priority === 'High')? 'Высокий' :
                            (($_priority === 'Medium')? 'Обычный' :
                            (($_priority === 'Low')? 'Низкий' : 'На будущее')));
                $message = "В задаче $_summary приоритет изменился на *{$priority}*";
                break;
            case self::STATUS_UPDATED:
                $message = "В задаче $_summary статус изменился на *{$status}*";
                break;
            case self::ISSUE_TYPE_UPDATED:
                $message = "Изменился тип задачи $_summary с _" . $change->getFromString() .  '_ на _' . $change->getToString() . '_';
                break;
            case self::ISSUE_DONE:
                $message = "Задача $_summary завершена";
                break;
            case self::ISSUE_CREATED:

                if ($this->data['issue']['fields']['issuetype']['subtask'] == true) {
                    $message = "Создана новая подзадачa $_summary для ".$this->data['issue']['fields']['parent']['key'] ;
                } else {
                    $message = "Создана новая задачa $_summary ";
                }
                break;
            case self::ISSUE_UPDATED:
                $message = "Обновлена задача $_summary";
                break;
            case self::ISSUE_DELETED:
                $message = "Удалена задача {$ikey}";
                break;
            case self::COMMENT_CREATED:
                $message = "К задаче $_summary добавлен новый комментарий";
                $_msg[] = '*Комментарий*: _' . $this->data['comment']['author']['displayName'] . '_ : ' . $this->data['comment']['body'];
                break;
            case self::COMMENT_UPDATED:
                $message = "В задаче $_summary обновлен комментарий";
                $_msg[] = '*Комментарий*: _' . $this->data['comment']['author']['displayName'] . '_ : ' . $this->data['comment']['body'];
                break;
            case self::COMMENT_DELETED:
                $message = "В задаче $_summary удален комментарий";
                $_msg[] = '*Комментарий*: _' . $this->data['comment']['author']['displayName'] . '_ : ' . $this->data['comment']['body'];
                break;
            case self::DESCRIPTION_UPDATED:
                $message = "В задаче $_summary обновлено описание";
                break;
            default:
                $message = 'Задача *'. $ikey .'*';
        }

        return $message;
    }

    private function menu($webhook)
    {
        switch ($webhook){
            case self::DUEDATE_CREATED:
            case self::DUEDATE_UPDATED:
            case self::DUEDATE_DELETED:
            case self::ASSIGNEE_CREATED:
            case self::ASSIGNEE_DELETED:
            case self::STATUS_UPDATED:
                $menu = self::FIELD_MENU;
                break;
            case self::ISSUE_DELETED:
                $menu = self::SPEAK_MENU;
                break;
            case self::COMMENT_CREATED:
            case self::COMMENT_UPDATED:
                $menu =  self::COMMENT_MENU;
                break;
            default:
                $menu = self::ISSUE_MENU;
        }

        return $menu;
    }

    public function sendStatus()
    {
        $src = json_decode($this->data_export['src'], true);
        $ikey = $this->data['issue']['key'];

        if (!empty($this->data['issue']['fields']['timeestimate'])) {
            $estimate = $this->convEstimateToDaysAndHours($this->data['issue']['fields']['timeestimate']);
        } elseif (!empty($this->data['issue']['fields']['timeoriginalestimate'])) {
            $estimate = $this->convEstimateToDaysAndHours($this->data['issue']['fields']['timeoriginalestimate']);
        } else {
            $estimate = '_не указана_';
        }
        $assignee    = $this->data['issue']['fields']['assignee']['displayName'] ?? '_не назначен_';
        $priority    = $this->data['issue']['fields']['priority']['name'];
        $status      = $src['fields']['status']['name'];
        $description = $this->data['issue']['fields']['description'] ?? '_не указано_';
        $duedate     = empty($src['fields']['duedate'])? '_не указан_' : date('d.m.y', strtotime($src['fields']['duedate']));
        $version     = $this->data['issue']['fields']['fixVersions'][0]['name'] ?? '_не указан_';

        if (!empty($src['fields']['attachment'])) {
            $this->prepareDescriptionWithImage($src['fields']['attachment'], $description);
        }

        $_msg = [
            '*Оценка*: '.$estimate,
            '*Версия*: '.$version,
            '*Срок выполнения*: '.$duedate,
            '*Исполнитель*: '.$assignee,
            '*Приоритет*: '.$priority,
            '*Статус*: '.$status,
            '*Описание*'."\n".$description
        ];

        $options = [
            'issue' => $ikey,
           // 'edit'  => true
        ];

        /*if (count($this->changeLog) == 1) {
            $menu = $this->menu($this->changeLog[0]->getWebhook());
        } else {
            $menu = self::ISSUE_MENU;
        }*/
        $menu = self::ISSUE_MENU;

        $message = '';
        foreach ($this->changeLog as $log) {
            $message .= $this->constructMessage($log, $_msg) . "\n";
        }
        $message .= implode("\n", $_msg);

        return compact('message', 'options', 'menu');
    }


    public function stat_fio($k_fio, $pr = 0)
    {

        $data = $this->data_export;

        $sql2 = "SELECT fio FROM jira_users WHERE `key`='$k_fio'";
        $row2 = $this->db->query($sql2, PDO::FETCH_ASSOC)->fetch();
        $fio = $row2['fio'];

        $dat = date('Y-m-d');
        if ($pr == 1)
        {
            $sql = "DELETE FROM jira_stat WHERE dat='$dat' and `key_fio`='{$k_fio}'";
            $this->db->query($sql);
        }

        $sql = "SELECT * FROM jira_stat WHERE dat='$dat' and `key_fio`='{$k_fio}'";
//echo $sql;
        $row = $this->db->query($sql, PDO::FETCH_ASSOC)->fetch();
        $current_day = $row;
        if ($row['id'] == false)
        {
            $sql2 = "select count(id) as cid from jira_issues where status_task='ОТКРЫТА' and `to`='{$k_fio}'";
            echo $sql2;
            $row2 = $this->db->query($sql2, PDO::FETCH_ASSOC)->fetch();
            $cid = $row2['cid'];

            $sql2 = "INSERT INTO `jira_stat` (`key_fio`, `fio`, `dat`, `day_open`, `day_max`, `day_min`, `day_current`, `day_close`) " .
                "VALUES ('$k_fio', '{$fio}', '$dat', '$cid', '$cid', '$cid', '$cid','$cid')";
            $this->db->query($sql2);
        }

        if ($pr == 0)
        {
            if ($data['action'] == 'jira:issue_created')
            {
                $current_day['day_current'] = $current_day['day_current'] + 1;
                if ($current_day['day_max'] <= $current_day['day_current'])
                {
                    $current_day['day_max'] = $current_day['day_current'];
                }
                if ($current_day['day_min'] >= $current_day['day_current'])
                {
                    $current_day['day_min'] = $current_day['day_current'];
                }
                $sql = "UPDATE jira_stat SET day_current={$current_day['day_current']}, 
        day_close={$current_day['day_current']}, 
        day_min={$current_day['day_min']},
        day_max={$current_day['day_max']} 
        WHERE dat='$dat' and `key_fio`='{$k_fio}'";
                $this->db->query($sql);
            }
            elseif ($data['status_task'] != 'ОТКРЫТА')
            {
                $sql2 = "SELECT * FROM jira_issues WHERE `key`='{$data['key']}'";
                //echo $sql2;
                $res2 = $this->db->query($sql2);
                $row2 = mysql_fetch_assoc($res2);
                //print_r($row2);
                //exit;
                if ($row2['status_task'] == 'ОТКРЫТА')
                {
                    $current_day['day_current'] = $current_day['day_current'] - 1;
                    if ($current_day['day_max'] <= $current_day['day_current'])
                    {
                        $current_day['day_max'] = $current_day['day_current'];
                    }
                    if ($current_day['day_min'] >= $current_day['day_current'])
                    {
                        $current_day['day_min'] = $current_day['day_current'];
                    }
                    $sql = "UPDATE jira_stat SET day_current={$current_day['day_current']}, 
                day_close={$current_day['day_current']}, 
                day_min={$current_day['day_min']},
                day_max={$current_day['day_max']} 
                WHERE dat='$dat' and `key_fio`='{$k_fio}'";
                    $this->db->query($sql);
                }
            }
        }

    }
}