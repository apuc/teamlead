<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class VersionsCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'versions';
    protected $description = 'Меню для работы с версиями фикса к задаче';
    protected $usage       = '/versions';

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
        $versions = $this->issue->getVersions();
        if (empty($versions)) {
            $reply_markup = (new Keyboard(
                ['Добавить версию'],
                ['« Задача']
            ))->setResizeKeyboard(true);
        } else {
            $reply_markup = (new Keyboard(
                ['Текущие версии:'.implode(',',$versions)],
                ['Добавить версию'],
                ['Удалить версию'],
                ['« Задача']
            ))->setResizeKeyboard(true);
        }
        return $this->replyToChat('Версии', compact('reply_markup'));
    }
}