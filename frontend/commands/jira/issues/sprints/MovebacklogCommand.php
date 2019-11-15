<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class MovebacklogCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'movebackloj';
    protected $description = 'Задача в backlog';
    protected $usage       = '/movebackloj';

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
        $this->jira->moveIssueToBacklog($this->issue->key);

        return $this->telegram->executeCommand('issuepanel');
    }
}