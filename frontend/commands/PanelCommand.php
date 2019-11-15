<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use common\modules\staff\records\Staff;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class PanelCommand extends UserCommand
{
    use PreExecuteTrait;
    /**
     * @var string Command Name
     */
    protected $name = "panel";

    /**
     * @var string Command Description
     */
    protected $description = "Главная панель";

    /**
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute()
    {
        $this->executeByRole();
    }

    /**
     * Execute teamlead command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function executeTeamlead()
    {
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $user    = $message->getFrom();
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        $items = [];

       /* $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        if (!empty(\Yii::$app->issue->key)) {
            $state = 1;
            $items[] = 'Последняя задача '.\Yii::$app->issue->key;
        }
        */
        $items[] = [
            'Задачи',
            'Задачи для оценки',
            "\u{1F528} Создать задачу",
            "\u{23F0} Напоминания"
        ];

       /* switch ($state) {
            case 0:
                $conversation->stop();
                $result = $this->replyToChat(
                    'Панель управления', [
                    'reply_markup' => new Keyboard(...$items)
                ]);
                break;
            case 1:
                $result = $this->replyToChat(
                    'Панель управления', [
                    'reply_markup' => new Keyboard(...$items)
                ]);
                break;
        }*/
        $result = $this->replyToChat(
            'Панель управления', [
            'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
        ]);
        return $result;
    }

    /**
     * Execute worker command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function executeWorker()
    {
        $reply_markup = new Keyboard(
            ['Задачи'],
            ['Планирование'],
            ["\u{23F0} Напоминания"],
            ["\u{1F5E3}Пообщаться"]
        );

        $this->replyToChat(
            'Панель управления', compact('reply_markup')
        );
    }
}