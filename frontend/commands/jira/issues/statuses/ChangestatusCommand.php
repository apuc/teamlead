<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\statuses\records\Status;
use frontend\commands\UserCommand;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class ChangestatusCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'changestatus';
    protected $description = 'Меню для работы с метками к задаче';
    protected $usage       = '/changestatus';

    /**
     * @var JiraApi
     */
    protected $jira;
    /**
     * @var Issue
     */
    private $issue;

    public function preExecute()
    {
        $this->checkIssue();
        return parent::preExecute();
    }


    public function execute()
    {
        $text    = trim($this->getMessage()->getText(true));

        $conversation = new Conversation($this->getUserId(), $this->getChatId(), $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        $statuses = $this->jira->getStatuses($this->issue->key);

        switch ($state) {
            case 0:

                if (empty($statuses)) {
                    $this->replyToChat('Статуса для изменения нет');
                    $conversation->stop();
                    return $this->telegram->executeCommand('issuepanel');
                }

                $items = [];
                foreach ($statuses as $status) {
                     $items[] = [ $status['to']['name'] ];
                }
                $items[] = '« Статус';

                $result = $this->replyToChat(
                    'Выберите статус', [
                    'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
                ]);
                $notes['state'] = 1;
                $conversation->update();
                break;
            case 1:
                if ('« Статус' === $text) {
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('statuses');

                } elseif (in_array($text, $statuses)) {
                    $conversation->stop();
                    $status = Status::byName($text);
                    $this->jira->setStatus($this->issue->key, $status->jira_id);
                    $result = $this->telegram->executeCommand('issuepanel');

                } else {
                    $result = $this->replyToChat(
                        'Неверный статус!!! Повторите ввод',
                        compact('reply_markup')
                    );
                }
        }

        return $result;
    }
}