<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\priorities\records\Priority;
use common\modules\priorities\services\PriorityService;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class ChangepriorityCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'changepriority';
    protected $description = 'Изменение приоритета в задаче';
    protected $usage       = '/changepriority';
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

        $prioritiyNames = PriorityService::getPriorityNames();

        $items = array_merge($prioritiyNames, ['« Приоритет']);

        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        switch ($state) {
            case 0:
                $notes['state'] = 1;
                $conversation->update();
                $result = $this->replyToChat('Изменить приоритет', [
                    'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
                ]);
                break;

            case 1:

                if (in_array($text, $prioritiyNames)) {
                    $this->jira->setPriority($this->issue->key, Priority::byName($text)->jira_id);
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('issuepanel');

                } elseif ('« Приоритет' === $text) {
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('priority');
                } else {
                    $result = $this->replyToChat('Неверный приоритет! Повторите выбор', [
                        'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
                    ]);
                }

                break;
        }



        return $result;
    }
}