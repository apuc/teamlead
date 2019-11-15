<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use backend\modules\labels\Label;
use common\modules\labels\services\LabelService;
use common\modules\versions\services\VersionService;
use JiraRestApi\Issue\Issue;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\debug\Panel;

class CreateversionCommand extends UserCommand
{
    protected $name = 'createversion';
    protected $description = 'Создание собственной метки к задаче';
    protected $usage = '/createversion';
    protected $version = '1.0.0';
    /**
     * @var bool
     */
    protected $need_mysql = true;
    /**
     * @var bool
     */
    protected $private_only = true;
    /**
     * @var JiraApi
     */
    protected $jira;
    /**
     * @var Issue
     */
    private $issue;

    /**
     * {@inheritdoc}
     */
    public function preExecute()
    {
        if (!empty(\Yii::$app->issue->key)) {
            $this->jira  = \Yii::$app->get('jira');
            $this->issue = \Yii::$app->get('issue');
            return parent::preExecute();
        }
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

        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        switch ($state) {
            case 0:
                $result = $this->replyToChat(
                    'Введите версию', [
                    'reply_markup' => (new Keyboard('Отмена'))->setResizeKeyboard(true)
                ]);
                $notes['state'] = 1;
                $conversation->update();
                break;
            case 1:
                if ('Отмена' === $text) {
                    $conversation->cancel();
                    $result = $this->telegram->executeCommand('versions');
                } elseif (preg_match('/\d+.\d/', $text)) {
                    VersionService::make(['code'=>$text]);
                    $this->jira->addFixVersion($this->issue->key, $text);

                    $conversation->stop();
                    $result = $this->telegram->executeCommand('issuepanel');

                } else {
                    $result = $this->replyToChat(
                        'Ошибка формата! (формат <0-9>.<0-9>. Повторите ввод', [
                        'reply_markup' => (new Keyboard('Отмена'))->setResizeKeyboard(true)
                    ]);

                }
        }

        return $result;
    }
}