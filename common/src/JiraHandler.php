<?php

namespace jira;

use events\ButtonsTrait;
use events\JiraEvent;
use services\ChannelService;
use services\JiraService;
use services\UserService;
use telegram\InlineKeyboardBuilder;
use telegram\SessionData;
use telegram\TelegramApi;
use telegram\TelegramBot;

class JiraHandler
{
    use ButtonsTrait,
        JiraEvent;
    /**
     * @var TelegramApi
     */
    protected $telegram;

    /**
     * @var JiraWebhook
     */
    protected $webhook;

    public function __construct(JiraWebhook $jira_webhook, TelegramApi $telegram)
    {
        $this->webhook = $jira_webhook;
        $this->telegram = $telegram;
    }

    public function run()
    {
        $data = $this->webhook->getParsedData();

        $this->storeJiraLog($data);
        $this->storeJiraIssue($data);
        $this->storeUsers($data);

        $result = $this->webhook->sendStatus();

        $text    = $result['message'];
        $options = $result['options'];
        $menu    = $result['menu'];

        $channels = ChannelService::channels();
        $parse_mode = 'Markdown';

        foreach ($channels as $channel) {
            $chat_id = $channel['chid'];

           if ('private' === $channel['type']) {
                if (false === $this->toSend($data, $chat_id)) {
                    continue;
                }
            }

            $session = new SessionData($chat_id);

            if (isset($session['nonotify'])) {
                continue;
            }

            if ('343490789' != $channel['chid'] ) {
                continue;
            }


            $reply_markup = $this->jiraWebhookMenu($chat_id, $options);

            /*switch ($menu) {
                case JiraWebhook::ISSUE_MENU:
                    $reply_markup = $this->issueMenu($chat_id, $options);
                    break;
                case JiraWebhook::FIELD_MENU:
                    $reply_markup = $this->fieldMenu($chat_id, $options);
                    break;
                case JiraWebhook::SPEAK_MENU:
                    $reply_markup = $this->speakMenu($options);
                    break;
                case JiraWebhook::COMMENT_MENU:
                    $reply_markup = $this->commentMenu($chat_id,  $options);
                    break;
                default:
                    continue 2;
            }*/

            $disable_web_page_preview = true;

            $result = $this->telegram->sendMessage(compact('chat_id', 'reply_markup', 'parse_mode', 'text', 'disable_web_page_preview'));

            if ($result['ok'] != 1) {
                $this->saveMessage($chat_id, $text, $reply_markup, 0);
            }
        }
    }

    private function toSend(array $d, $uid): bool
    {
        $names = [];
        if (!empty($d['to'])) {
            $names[] = $d['to'];
        }
        if (!empty($d['author'])) {
            $names[] = $d['author'];
        }

        return UserService::isJiraReceive($uid, $names);
    }


    public function saveMessage($chid, string $msg, string $actions, int $sended)
    {
        JiraService::storeMessage($chid,$msg, $actions, $sended);
    }

    public function storeJiraLog($data)
    {
        JiraService::storeLog($data);
    }

    public function storeJiraIssue($data)
    {
        JiraService::storeIssue($data);
    }

    protected function storeUsers($data)
    {
        if (!empty($data['from'])) {
            JiraService::storeUser($data['from'], $data['from_name']);
        }

        if (!empty($data['to'])) {
            JiraService::storeUser($data['to'], $data['to_name']);
        }

        if (!empty($data['author'])) {
            JiraService::storeUser($data['author'], $data['author_name']);
        }

        if (!empty($data['reporter'])) {
            JiraService::storeUser($data['reporter'], $data['reporter_name']);
        }
    }

}