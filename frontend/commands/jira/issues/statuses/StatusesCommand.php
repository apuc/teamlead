<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class StatusesCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'statuses';
    protected $description = 'Меню для работы с метками к задаче';
    protected $usage       = '/statuses';

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
        $items = [];
        if (!empty($this->issue->status->name)) {
            $items []= [
                'Текущий: '.$this->issue->status->name
            ];
        }

        $items []= ['Изменить статус'];
        $items []= ['« Задача'];

        $reply_markup = (new Keyboard(...$items))->setResizeKeyboard(true);

        return $this->replyToChat('Метки', compact('reply_markup'));
    }
}