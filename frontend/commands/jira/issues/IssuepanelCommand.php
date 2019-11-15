<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use frontend\commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;

class IssuepanelCommand extends UserCommand
{
    protected $name = 'issuepanel';
    protected $description = 'Панель работы с задачей';
    protected $usage = '/issuepanel';
    protected $version = '1.0.0';

    public function execute()
    {
        $reply_markup = new Keyboard([
                "\u{1F517} Связать задачу",
                "\u{1F4C5} Срок выполнения",
                "\u{1F522} Версии"
            ], [
                "\u{1F4CD} Метки",
                "\u{1F525} Приоритет",
                "\u{1F464} Исполнители"
            ], [
                "\u{26A1}  Спринты",
                "\u{1F9E9} Компоненты",
                "\u{1F4AC} Комментарии"
            ], [
                "\u{1F553} Оценка времени",
                "\u{2705}  Статус",
                "\u{1F5E3} Пообщаться"
            ], [
                "Детали задачи",
                "Сменить задачу",
                "На главную"
            ]
        );

        return $this->replyToChat('Главная панель', compact('reply_markup'));
    }
}