<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class IssuesCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'issues';
    protected $description = 'Меню для выбора и поиска задачи';
    protected $usage       = '/issues';


    public function execute()
    {
        $this->executeByRole();
    }

    public function executeTeamlead()
    {
        return $this->replyToChat('Задачи', [
            'reply_markup' => (new Keyboard(
                ['Мои задачи', 'Текущие задачи'],
                ["\u{1F50E} Поиск задачи", "\u{1F528} Создать задачу"],
                ['На главную']
            ))->setResizeKeyboard(true)
        ]);
    }

    public function executeWorker()
    {
        return $this->replyToChat('Задачи', [
            'reply_markup' => (new Keyboard(
                ['Мои задачи', 'Текущие задачи'],
                ["\u{1F50E} Поиск задачи", "\u{1F528} Создать задачу"],
                ['На главную']
            ))->setResizeKeyboard(true)
        ]);
    }
}