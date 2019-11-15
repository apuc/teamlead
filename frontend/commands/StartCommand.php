<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;


/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class StartCommand extends UserCommand
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start Command to get you started";

    /**
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute()
    {
        $this->telegram->executeCommand('panel');
        Request::sendMessage([
            'chat_id' => $this->getMessage()->getChat()->getId(),
            'text' => json_encode($this->getMessage()),
        ]);

    }
}