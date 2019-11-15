<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class PriorityCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'priority';
    protected $description = 'Меню для работы с приоритетами к задаче';
    protected $usage       = '/priority';
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
        $reply_markup = (new Keyboard(
            ['Текущий: '.$this->issue->priority->name],
            ['Изменить приоритет'],
            ['« Задача']
        ))->setResizeKeyboard(true);

        return $this->replyToChat('Приоритет', compact('reply_markup'));
    }
}