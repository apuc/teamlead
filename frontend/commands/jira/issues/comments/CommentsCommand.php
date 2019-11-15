<?php
namespace Longman\TelegramBot\Commands\UserCommands;

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
class CommentsCommand extends UserCommand
{
    use PreExecuteTrait;

    /**
     * @var string Command Name
     */
    protected $name = "comments";

    /**
     * @var string Command Description
     */
    protected $description = "Меню с комментариями";

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
        $reply_markup = (new Keyboard(
            ['Вывести комментарии'],
            ['Написать комментарий'],
            ['« Задача']
        ))->setResizeKeyboard(true);

        return $this->replyToChat('Комментарии', compact('reply_markup'));

    }
}