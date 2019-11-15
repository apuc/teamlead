<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\labels\records\Label;
use common\modules\labels\services\LabelService;
use common\modules\versions\services\VersionService;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class AddversionCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'addversion';

    protected $description = 'Добавление метки к задаче';

    protected $usage = '/addversion';

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

        $versionNames = VersionService::getVersionNames();

        $items = array_merge($versionNames,['Создать версию', '« Версии']);

        $reply_markup = (new Keyboard(...$items))->setResizeKeyboard(true);

        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        switch ($state) {
            case 0:
                $notes['state'] = 1;
                $conversation->update();

                $result = $this->replyToChat('Укажите версию или создайте новую', compact('reply_markup'));
                break;
            case 1:
                if (in_array($text, $versionNames)) {
                    $this->jira->addFixVersion($this->issue->key, $text);
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('issuepanel');

                } elseif ('Создать версию' === $text) {
                    $conversation->stop();
                    $result =$this->telegram->executeCommand('createversion');

                } elseif ('« Версии' === $text) {
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('versions');

                } else {
                    $result = $this->replyToChat(
                        'Неверный ввод!!! Укажите версию или создайте новую',
                        compact('reply_markup')
                    );
                }
        }

        return $result;

    }
}