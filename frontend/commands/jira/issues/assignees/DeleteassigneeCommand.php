<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class DeleteassigneeCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'deleteassignee';
    protected $description = 'Меню для работы с исполнителями к задаче';
    protected $usage       = '/deleteassignee';
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
        $this->jira->removeAssign($this->issue->key);

        return (new IssuepanelCommand($this->telegram, $this->update))->preExecute();
    }
}