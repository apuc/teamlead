<?php
namespace frontend\commands;

use Longman\TelegramBot\Commands\UserCommand as LongmanUserCommand;
use Longman\TelegramBot\Entities\Message;

abstract class UserCommand extends LongmanUserCommand
{
    public function getMessage() : Message
    {
        $type = $this->getUpdate()->getUpdateType();
        if ('callback_query' === $type) {
            return $this->getUpdate()->getCallbackQuery()->getMessage();
        }
        if ('edited_message' === $type) {
            return $this->getUpdate()->getEditedMessage();
        }
        return $this->getUpdate()->getMessage();
    }

    public function getChatId()
    {
        return $this->getMessage()->getChat()->getId();
    }

    public function getUserId()
    {
        return $this->getMessage()->getFrom()->getId();
    }

    public function getMessageId()
    {
        return $this->getMessage()->getMessageId();
    }
}