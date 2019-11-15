<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class StatussprintCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'statussprint';
    protected $description = 'Статус спринта';
    protected $usage       = '/statussprint';

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



        return Request::emptyResponse();
    }
}