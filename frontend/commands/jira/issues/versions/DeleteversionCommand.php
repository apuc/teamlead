<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\labels\records\Label;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;

class DeleteversionCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'deleteversion';

    protected $description = 'Удаление версии в задаче';

    protected $usage = '/deleteversion';
    /**
     * @var JiraApi
     */
    protected $jira;
    /**
     * @var Issue
     */
    private $issue;

    /**
     * {@inheritdoc}
     */
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

        $versions = $this->issue->getVersions();

        if (empty($versions)) {
            $this->replyToChat('Версий для удаления нет');
        } else {
            $items = array_merge($versions,['« Версии']);

            $conversation = new Conversation($user_id, $chat_id, $this->getName());
            $notes = &$conversation->notes;
            !is_array($notes) && $notes = [];
            $state = $notes['state'] ?? 0;

            switch ($state) {
                case 0:
                    $this->replyToChat(
                        'Укажите версию для удаления', [
                            'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
                    ]);
                    $notes['state'] = 1;
                    $conversation->update();
                    break;

                case 1:
                    if (($index = array_search($text, $versions)) !== false) {
                        $this->jira->deleteFixVersion($this->issue->key, $text);

                        $conversation->stop();
                        $result = $this->telegram->executeCommand('issuepanel');

                    } elseif ('« Версии' === $text) {
                        $conversation->stop();
                        $result =  $this->telegram->executeCommand('versions');

                    } else {
                        $result = $this->replyToChat(
                            'Неверная версия!!! Повторите ввод', [
                            'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
                        ]);
                    }
            }
        }
        return $result;
    }
}