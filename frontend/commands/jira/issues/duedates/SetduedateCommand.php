<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use DateTime;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class SetduedateCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'setduedate';
    protected $description = 'Меню для работы с приоритетами к задаче';
    protected $usage       = '/setduedate';
    /**
     * @var JiraApi
     */
    protected $jira;
    /**
     * @var Issue
     */
    protected $issue;

    public function preExecute()
    {
        $this->checkIssue();
        return parent::preExecute();
    }

    public function execute()
    {
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $user    = $message->getFrom();
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        switch ($state) {
            case 0:
                $notes['state'] = 1;
                $conversation->update();
                $result = $this->replyToChat('Укажите срок выполнения к задаче YYYY-MM-DD', [
                    'reply_markup' => (new Keyboard(['Отмена']))->setResizeKeyboard(true)
                ]);
                break;

            case 1:
                if (DateTime::createFromFormat('Y-m-d', $text) !== false) {
                    $conversation->stop();
                    $this->jira->setDuedate($this->issue->key, $text);
                    $result = $this->telegram->executeCommand('issuepanel');
                }  elseif ('Отмена' === $text) {
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('duedate');
                } else {
                    $result = $this->replyToChat('Неверный формат даты YYYY-MM-DD! Повторите выбор', [
                        'reply_markup' => (new Keyboard(['Отмена']))->setResizeKeyboard(true)
                    ]);
                }
                break;
        }
        return $result;
    }
}