<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class DuedateCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'duedate';
    protected $description = 'Меню для работы с приоритетами к задаче';
    protected $usage       = '/duedate';
    /**
     * @var JiraApi
     */
    protected $jira;
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
        if (!empty($this->issue->duedate)) {
            $items = [
                'Текущий: '.date('d.m.y',strtotime($this->issue->duedate)),
                'Изменить срок выполнения',
                'Удалить срок выполнения',
                '« Задача'
            ];
        } else {
            $items = [
                'Установить срок выполнения',
                '« Задача'
            ];
        }
        $reply_markup = (new Keyboard(...$items))->setResizeKeyboard(true);

        return $this->replyToChat('Приоритет', compact('reply_markup'));
    }
}