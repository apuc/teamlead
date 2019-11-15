<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\labels\records\Label;
use common\modules\labels\services\LabelService;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\helpers\ArrayHelper;

class AddsprintCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'addsprint';

    protected $description = 'Добавление задачи в спринт';

    protected $usage = '/addsprint';

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

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $user    = $message->getFrom();
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        $boards  = $this->jira->getBoards();

        $sprints = $this->jira->getSprints($boards[$this->issue->project]['id']);
        
        $sprintNames = ArrayHelper::getColumn($sprints, 'name');
        $sprintIds   = ArrayHelper::getColumn($sprints, 'id');

        $items = array_merge($sprintNames,['« Спринты']);

        $reply_markup = (new Keyboard(...$items))->setResizeKeyboard(true);

        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        switch ($state) {
            case 0:
                $notes['state'] = 1;
                $conversation->update();

                $result = $this->replyToChat('Укажите спринт для задачи', compact('reply_markup'));
                break;
            case 1:
                if (($index = array_search($text, $sprintNames)) !== false) {
                    $this->jira->moveIssueToSprint($sprintIds[$index], $this->issue->key);
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('issuepanel');

                } elseif ('« Спринты' === $text) {
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('sprints');

                } else {
                    $result = $this->replyToChat(
                        'Неверный ввод!!! Укажите верный спринт',
                        compact('reply_markup')
                    );
                }
        }

        return $result;

    }
}