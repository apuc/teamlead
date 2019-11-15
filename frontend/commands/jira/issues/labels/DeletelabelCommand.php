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

class DeletelabelCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name = 'deletelabel';

    protected $description = 'Удаление метки к задаче';

    protected $usage = '/deletelabel';
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
        $text    = trim($this->getMessage()->getText(true));

        $labels = $this->issue->getLabels();

        if (empty($labels)) {
            $this->replyToChat('Меток для удаления нет');
        } else {
            $items = array_merge($labels,['« Метки']);

            $conversation = new Conversation($this->getUserId(), $this->getChatId(), $this->getName());
            $notes = &$conversation->notes;
            !is_array($notes) && $notes = [];
            $state = $notes['state'] ?? 0;

            switch ($state) {
                case 0:
                    $this->replyToChat(
                        'Укажите метку для удаления', [
                            'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
                    ]);
                    $notes['state'] = 1;
                    $conversation->update();
                    break;

                case 1:
                    if (($index = array_search($text, $labels)) !== false) {
                        unset($labels[$index]);
                        $this->jira->deleteLabel($this->issue->key, $labels);

                        $conversation->stop();
                        $result = $this->telegram->executeCommand('issuepanel');

                    } elseif ('« Метки' === $text) {
                        $conversation->stop();
                        $result =  $this->telegram->executeCommand('labels');

                    } else {
                        $result = $this->replyToChat(
                            'Неверная метка!!! Повторите ввод', [
                            'reply_markup' => (new Keyboard(...$items))->setResizeKeyboard(true)
                        ]);
                    }
            }
        }
        return $result;
    }
}