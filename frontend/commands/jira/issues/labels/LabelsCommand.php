<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class LabelsCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'labels';
    protected $description = 'Меню для работы с метками к задаче';
    protected $usage       = '/labels';

    public function preExecute()
    {
        $this->checkIssue();
        return parent::preExecute();
    }


    public function execute()
    {
        $reply_markup = (new Keyboard(
            ['Текущие метки'],
            ['Добавить метку'],
            ['Удалить метку'],
            ['« Задача']
        ))->setResizeKeyboard(true);

        return $this->replyToChat('Метки', compact('reply_markup'));
    }
}