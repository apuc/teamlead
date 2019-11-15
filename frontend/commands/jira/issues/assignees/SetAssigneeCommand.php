<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\users\records\User;
use common\modules\users\services\UserService;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class SetAssigneeCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'setassignee';
    protected $description = 'Выбор исполнителя на задачу';
    protected $usage       = '/setassignee';
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

        $users = UserService::getNames();

        if (empty($users)) {
            $this->replyToChat('Список пользователей пуст');
            return (new IssuepanelCommand($this->telegram, $this->update))->preExecute();
        }

        $items = array_merge($users, ['« Исполнители']);
        $reply_markup = (new Keyboard(...$items))->setResizeKeyboard(true);

        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        switch ($state) {
            case 0:
                $notes['state'] = 1;
                $conversation->update();

                $result = $this->replyToChat('Выберите исполнителя', compact('reply_markup'));
                break;
            case 1:

                if (in_array($text, $users)) {
                    $this->jira->assign($this->issue->key, User::byName($text)->key);
                    $conversation->stop();
                    $result = (new IssuepanelCommand($this->telegram, $this->update))->preExecute();

                } elseif ('« Исполнители' === $text) {
                    $conversation->stop();
                    $result = (new AssigneesCommand($this->telegram, $this->update))->preExecute();

                } else {
                    $result = $this->replyToChat(
                        'Неверный пользователь!!! Повторите выбор',
                        compact('reply_markup')
                    );
                }
        }

        return $result;
    }
}