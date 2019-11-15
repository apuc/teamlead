<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use common\modules\issues\records\Issue;
use common\modules\staff\records\Staff;
use common\modules\users\records\User;
use frontend\commands\UserCommand;
use frontend\traits\PreExecuteTrait;

use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class MyissuesCommand extends UserCommand
{
    use PreExecuteTrait;

    protected $name        = 'myissues';
    protected $description = 'Меню для выбора и поиска задачи';
    protected $usage       = '/myissues';
    protected $limit       = 10;


    public function execute()
    {
        $user = Staff::byTlgUserId($this->getChatId());

        $config = $this->telegram->getCommandConfig($this->name);
        $page  = $config['page'] ?? 1;
        $start = !empty($config['page']);

        $total  = Issue::find()->byJiraUser($user)->count();
        $issues = Issue::find()->byJiraUser($user)->offset(($page - 1)*$this->limit )->limit($this->limit)->all();

        $items = [];
        /** @var Issue $issue **/
        foreach ($issues as $index => $issue) {
            $items[] = [
                [
                    'text' => $issue->key,
                    'callback_data' => json_encode([
                        'i' => $issue->key,
                        'c' => 'issuepanel'
                    ])
                ],[
                    'text' => 'Jira ссылка',
                    'url'  => $issue->getHref()
                ]
            ];
        }

        $buttonItems = [];
        if ($page > 1) {
            $buttonItems[] = [
                'text' => '◄',
                'callback_data' => json_encode([
                    'p' => $page - 1,
                    'c' => $this->name
                ])
            ];
        }
        if (($page * $this->limit) < $total) {
            $buttonItems[] = [
                'text' => '►',
                'callback_data' => json_encode([
                    'p' => $page + 1,
                    'c' => $this->name
                ])
            ];
        }
        $items[] = $buttonItems;

        $reply_markup = new InlineKeyboard(...$items);
        if ($start) {
            return Request::editMessageReplyMarkup([
                'chat_id' => $this->getChatId(),
                'message_id' => $this->getMessageId(),
                'reply_markup' => $reply_markup
            ]);
        } else {
            return $this->replyToChat('Мои задачи', compact('reply_markup'));
        }

    }

}