<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class CurrentlabelsCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'currentlabels';

    protected $description = 'Текущие метки в задаче';

    protected $usage = '/currentlabels';
    /**
     * @var JiraApi
     */
    protected $jira;
    /**
     * @var Issue
     */
    private $issue;

    public function preExecute()
    {
        $this->checkIssue();
        return parent::preExecute();
    }

    public function execute()
    {
        $labels = $this->issue->getLabels();

        if (empty($labels)) {
            $result = $this->replyToChat('Меток нет');
        } else {
            $text = "Метки:";
            foreach ($labels as $label) {
                $text .= "\n{$label}";
            }
            $result = $this->replyToChat($text);
        }
        return $result;
    }
}