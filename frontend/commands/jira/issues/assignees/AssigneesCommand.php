<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class AssigneesCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'assignees';
    protected $description = 'Меню для работы с исполнителями к задаче';
    protected $usage       = '/assignees';
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
        if ($this->issue->assignee) {
            $reply_markup = (new Keyboard(
                ['Текущий: '.$this->issue->assignee->name],
                ['Переназначить исполнителя'],
                ['Снять исполнителя'],
                ['« Задача']
            ))->setResizeKeyboard(true);
        } else {
            $reply_markup = (new Keyboard(
                ['Назначить исполнителя'],
                ['« Задача']
            ))->setResizeKeyboard(true);
        }

        return $this->replyToChat('Исполнители', compact('reply_markup'));
    }
}