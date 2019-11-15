<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class TobindCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'tobind';
    protected $description = 'Меню для работы с метками к задаче';
    protected $usage       = '/tobind';

    public function preExecute()
    {
        $this->checkIssue();
        return parent::preExecute();
    }

    public function execute()
    {
        $reply_markup = (new Keyboard(
            ['Блокирует связанную'],
            ['Блокируется связанной'],
            ['Дублирует'],
            ['Ссылается'],
            ['« Задача']
        ))->setResizeKeyboard(true);

        return $this->replyToChat('Связать задачу', compact('reply_markup'));
    }
}