<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\labels\records\Label;
use frontend\commands\UserCommand;
use frontend\traits\PreExecuteTrait;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;

class DeletecomponentCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'deletecomponent';

    protected $description = 'Удаление метки к задаче';

    protected $usage = '/deletecomponent';
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
        $text = trim($this->getMessage()->getText(true));

        $components = $this->issue->getComponents();

        if (empty($components)) {
            return $this->replyToChat('Компонентов для удаления нет');
        } else {
            $componentNames = array_map(function ($component) {
                return $component['name'];
            }, $components);

            $items = array_merge($componentNames,['« Компоненты']);

            $conversation = new Conversation($this->getUserId(), $this->getChatId(), $this->getName());
            $notes = &$conversation->notes;
            !is_array($notes) && $notes = [];
            $state = $notes['state'] ?? 0;

            switch ($state) {
                case 0:
                    $this->replyToChat(
                        'Укажите компонент для удаления', [
                            'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
                    ]);
                    $notes['state'] = 1;
                    $conversation->update();
                    break;

                case 1:

                    if (($index = array_search($text, $componentNames)) !== false) {
                        $this->jira->deleteIssueComponent($this->issue->key, 0);
                        $conversation->stop();
                        $result = $this->telegram->executeCommand('issuepanel');

                    } elseif ('« Компоненты' === $text) {
                        $conversation->stop();
                        $result =  $this->telegram->executeCommand('components');

                    } else {
                        $result = $this->replyToChat(
                            'Неверный компонент!!! Повторите ввод', [
                            'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
                        ]);
                    }
            }
        }
        return $result;
    }
}