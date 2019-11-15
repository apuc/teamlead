<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\labels\records\Label;
use common\modules\labels\services\LabelService;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class AddlabelCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'addlabel';

    protected $description = 'Добавление метки к задаче';

    protected $usage = '/addlabel';

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
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $user    = $message->getFrom();
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        $labelNames = LabelService::getLabelName();

        $items = array_merge($labelNames,['Создать метку', '« Метки']);

        $reply_markup = (new Keyboard(...$items))->setResizeKeyboard(true);

        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        switch ($state) {
            case 0:
                $notes['state'] = 1;
                $conversation->update();

                $result = $this->replyToChat('Укажите метку или создайте новую', compact('reply_markup'));
                break;
            case 1:
                if (in_array($text, $labelNames)) {
                    $this->jira->addLabel($this->issue->key, $text);
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('issuepanel');

                } elseif ('Создать метку' === $text) {
                    $conversation->stop();
                    $result =$this->telegram->executeCommand('createlabel');

                } elseif ('« Метки' === $text) {
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('labels');

                } else {
                    $result = $this->replyToChat(
                        'Неверный ввод!!! Укажите метку или создайте новую',
                        compact('reply_markup')
                    );
                }
        }

        return $result;

    }
}