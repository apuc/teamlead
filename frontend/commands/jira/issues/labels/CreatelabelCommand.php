<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use backend\modules\labels\Label;
use common\modules\labels\services\LabelService;
use frontend\commands\UserCommand;
use frontend\traits\PreExecuteTrait;
use JiraRestApi\Issue\Issue;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\debug\Panel;

class CreatelabelCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'createlabel';
    protected $description = 'Создание собственной метки к задаче';
    protected $usage = '/createlabel';
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
        $this->checkIssue();
        return parent::preExecute();
    }
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $text    = trim($message->getText(true));

        $conversation = new Conversation($this->getUserId(), $this->getChatId(), $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        switch ($state) {
            case 0:
                $result = $this->replyToChat(
                    'Введите метку', [
                    'reply_markup' => (new Keyboard('Отмена'))->setResizeKeyboard(true)
                ]);
                $notes['state'] = 1;
                $conversation->update();
                break;
            case 1:
                if ('Отмена' === $text) {
                    $conversation->cancel();
                    $result = $this->telegram->executeCommand('labels');

                } elseif (preg_match('/\s+/', $text)) {
                    $result = $this->replyToChat(
                        'Ошибка формата! не используйте знак пробел. Повторите ввод', [
                        'reply_markup' => (new Keyboard('Отмена'))->setResizeKeyboard(true)
                    ]);

                } else {
                    LabelService::make(['name'=>$text]);
                    $this->jira->addLabel($this->issue->key, $text);

                    $conversation->stop();
                    $result = $this->telegram->executeCommand('issuepanel');
                }
        }

        return $result;
    }
}