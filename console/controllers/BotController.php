<?php
namespace console\controllers;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\issues\services\IssueService;
use common\modules\users\records\User;
use common\modules\users\services\UserService;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use yii\base\Module;
use yii\console\Controller;


class BotController extends Controller
{
    /**
     * @var Telegram
     */
    protected $telegram;

    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        Telegram $telegram
    ) {
        parent::__construct($id, $module, $config);
        $this->telegram = $telegram;
    }

    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function actionWebhook($url)
    {
        $this->telegram->setWebhook($url);
    }

    public function actionImportIssue($key)
    {
        $issue = \Yii::$app->jira->getIssueAgile(strtoupper($key));
        IssueService::make($issue);
    }

    public function actionImportIssues()
    {
        $issues = \Yii::$app->jira->getAllIssues();

        foreach ($issues as $issue) {
            $issue = \Yii::$app->jira->getIssueAgile($issue['key']);
            IssueService::make($issue);
        }


    }
}