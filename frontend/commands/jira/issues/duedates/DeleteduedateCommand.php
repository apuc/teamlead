<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class DeleteduedateCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'deleteduedate';
    protected $description = 'Удаление срока выполнения';
    protected $usage       = '/deleteduedate';
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
        return $this->telegram->executeCommand('issuepanel');
    }
}