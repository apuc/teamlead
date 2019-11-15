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

class CurrentcomponentsCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'currentcomponents';

    protected $description = 'Текущие компоненты в задаче';

    protected $usage = '/currentcomponents';
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
        $components = $this->issue->getComponents();

        if (empty($components)) {
            $result = $this->replyToChat('Компонентов нет');
        } else {
            $text = "*Компоненты:*";
            foreach ($components as $component) {
                $text .= "\n{$component['name']}";
            }
            $result = $this->replyToChat($text, ['parse_mode'=>'Markdown']);
        }
        return $result;
    }
}