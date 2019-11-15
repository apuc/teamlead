<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class SprintsCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'sprints';
    protected $description = 'Меню для работы со спринтами в задаче';
    protected $usage       = '/sprints';

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
        $sprint = $this->issue->getSprint();
        if (empty($sprint)) {
            $reply_markup = (new Keyboard(
                ['Добавить в спринт'],
                ['« Задача']
            ))->setResizeKeyboard(true);
        } else {
            $reply_markup = (new Keyboard(
                ['Текущий спринт: '.$sprint['name']],
                ['Убрать из спринта'],
                ['Переместить'],
                ['Статус спринта'],
                ['« Задача']
            ))->setResizeKeyboard(true);
        }



        return $this->replyToChat('Спринты', compact('reply_markup'));
    }
}