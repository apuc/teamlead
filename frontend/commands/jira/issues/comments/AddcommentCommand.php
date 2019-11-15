<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;


/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class AddcommentCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = "addcomment";

    protected $description = "Добавление коментария";

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
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
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
                $result = $this->replyToChat(
                    'Введите комментарий к задаче', [
                    'reply_markup' => (new Keyboard('Отмена'))->setResizeKeyboard(true)
                ]);
                $notes['state'] = 1;
                $conversation->update();
                break;
            case 1:
                if ('Отмена' === $text) {
                    $conversation->cancel();
                    $result = $this->telegram->executeCommand('comments');

                } else {
                    $conversation->stop();
                    $this->jira->addComment($this->issue->key, $text);
                    $result = $this->telegram->executeCommand('issuepanel');
                }
        }

        return $result;

    }
}