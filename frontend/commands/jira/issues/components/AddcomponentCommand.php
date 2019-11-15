<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\labels\records\Label;
use common\modules\labels\services\LabelService;
use frontend\commands\UserCommand;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class AddcomponentCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'addcomponent';

    protected $description = 'Добавление компонента к задаче';

    protected $usage = '/addcomponent';

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
        $text = trim($this->getMessage()->getText(true));

        $conversation = new Conversation($this->getUserId(), $this->getChatId(), $this->getName());
        $notes = &$conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        $components = $this->jira->getComponents($this->issue->project);

        $componentNames = [];
        foreach ($components as $component) {
            $componentNames[$component['name']] = $component['id'];
        }

        $items = array_merge(array_keys($componentNames),['« Компоненты']);

        switch ($state) {
            case 0:
                $notes['state'] = 1;
                $conversation->update();

                $reply_markup = (new Keyboard(...$items))->setResizeKeyboard(true);

                $result = $this->replyToChat('Укажите компонент ', compact('reply_markup'));
                break;
            case 1:
                if (isset($componentNames[$text])) {
                    $this->jira->addComponentToIssue($this->issue->key, $componentNames[$text]);
                    $conversation->stop();
                    $result = $this->telegram->executeCommand('issuepanel');

                } elseif ('« Компоненты' === $text) {
                    $conversation->stop();
                    $result =  $this->telegram->executeCommand('components');

                } else {
                    $result = $this->replyToChat(
                        'Неверный ввод!!! Повторите ввод',
                        compact('reply_markup')
                    );
                }
        }

        return $result;

    }
}