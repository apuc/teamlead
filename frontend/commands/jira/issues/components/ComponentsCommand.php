<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use frontend\commands\UserCommand;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;


/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class ComponentsCommand extends UserCommand
{
    use PreExecuteTrait;

    /**
     * @var string Command Name
     */
    protected $name = "components";

    /**
     * @var string Command Description
     */
    protected $description = "Меню с компонентами";

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
            ['Текущие компоненты'],
            ['Добавить компонент'],
            ['Удалить компонент'],
            ['« Задача']
        ))->setResizeKeyboard(true);

        return $this->replyToChat('Компоненты', compact('reply_markup'));

    }
}