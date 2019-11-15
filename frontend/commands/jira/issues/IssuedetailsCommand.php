<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class IssuedetailsCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'issuedetails';
    protected $description = 'Меню для работы с исполнителями к задаче';
    protected $usage       = '/issuedetails';
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
        return $this->replyToChat(
            $this->issue->getLink()."\n".
            $this->issue->getDetails(),[
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true
        ]);
    }
}