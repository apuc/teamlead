<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;


class GenericCommand extends SystemCommand
{
    protected $name = 'Genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.0';

    public function execute()
    {
        Request::sendMessage([
            'chat_id' => '343490789',
            'text' => 'Your utf4448 text ...',
        ]);



        /*$text = trim($this->getMessage()->getText(true));

        $update = json_decode($this->update->toJson(), true);

        if ($text === 'Need some help') {
            $update['message']['text'] = '/help';
            return (new HelpCommand($this->telegram, new Update($update)))->preExecute();
        }
        if ($text === 'Who am I?') {
            $update['message']['text'] = '/whoami';
            return (new WhoamiCommand($this->telegram, new Update($update)))->preExecute();
        }

        return Request::emptyResponse();*/
    }
}