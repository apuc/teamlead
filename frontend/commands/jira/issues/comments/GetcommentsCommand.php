<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;


/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class GetcommentsCommand extends UserCommand
{
    use PreExecuteTrait;

    /**
     * @var string Command Name
     */
    protected $name = "getcomments";

    /**
     * @var string Command Description
     */
    protected $description = "Вывод коментариев";

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

    /**
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute()
    {
        $comments = $this->issue->getComments();

        if (empty($comments)) {
            $result = $this->replyToChat('Нет комментариев');

        } else {
            $text = '';
            foreach ($comments as $comment) {
                $datetime = date('d.m.y H:i', strtotime($comment['created']));
                $text .= "\n*{$comment['author']['displayName']}*  \[{$datetime}] : {$comment['body']}";
            }
            $result = $this->replyToChat($text,['parse_mode' => 'Markdown']);
        }
        return $result;

    }
}