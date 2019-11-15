<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use common\modules\telegram\records\Message;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommands\IssuepanelCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;
use yii\console\Controller;


class GenericmessageCommand extends SystemCommand
{
    protected $name = 'genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.0';

    public static function removeEmoji(string $string)
    {
        $clear_string = preg_replace ("/[^«a-zA-ZА-Яа-я\d\s]/ui","",$string);
        return mb_strtolower(trim($clear_string));
    }

    public function execute()
    {
        $commands = require \Yii::getAlias('@frontend').'/config/commands.php';
        $text = static::removeEmoji($this->getMessage()->getText(true));
        $chat_id = $this->getMessage()->getChat()->getId();
        $user_id = $this->getMessage()->getFrom()->getId();
        $message_id = $this->getMessage()->getMessageId();

        if (null !== $this->executeActiveConversation()) {
            return Request::emptyResponse();
        }

        if (isset($commands[$text])) {
            if (class_exists($commands[$text])) {

              /*  $last_message = Message::find()
                    ->where(['chat_id' => $chat_id])
                    ->orderBy(['date' => SORT_DESC])
                    ->one();

                Request::deleteMessage([
                    'chat_id'    => $last_message->chat_id,
                    'message_id' => $last_message->id - 1
                ]);*/

                Request::deleteMessage(compact('chat_id', 'message_id'));
                return (new $commands[$text]($this->telegram, $this->update))->preExecute();
            }
        }
        return Request::emptyResponse();
    }
}