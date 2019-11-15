<?php
namespace frontend\controllers;

use App\JiraApi;
use common\modules\issues\records\Issue;
use common\modules\telegram\records\Session;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Site controller
 */
class BotController extends Controller
{
    /**
     * @var Telegram
     */
    private $telegram;
    /**
     * @var Update
     */
    private $update;

    /**
     * @var JiraApi
     */
    private $jira;

    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        Telegram $telegram
    ) {
        parent::__construct($id, $module, $config);
        $this->telegram = $telegram;
        $this->jira = \Yii::$app->jira;
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionTest()
    {


       $result = $this->jira->getIssueAgile('OUR-3');

        echo "<pre>";
        print_r($result);
        echo "</pre>";
/*
        foreach ($issues as $issue) {
            if ($count++ > 4) break;
            $issue = $jira->getIssueAgile($issue['key']);
            echo "<pre>";
            print_r($issue);
            echo "</pre>";
        }*/
    }

    /**
     * @return Update
     */
    public function getWebhookUpdate(): Update
    {
        if (empty($this->update)) {
            $body = json_decode(file_get_contents('php://input'), true);
            $this->update = new Update($body);
        }
        return $this->update;
    }

    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function actionHook()
    {
        $update = $this->getWebhookUpdate();

        if ('callback_query' == $update->getUpdateType()) {
            $chat_id = $update->getCallbackQuery()->getMessage()->getChat()->getId();
        } elseif('edited_message' == $update->getUpdateType()) {
            $chat_id = $update->getEditedMessage()->getChat()->getId();
        } else {
            $chat_id = $update->getMessage()->getChat()->getId();
        }

        if (($session = Session::byChatId($chat_id))) {
            $issueKey = $session->getValue('issue');

            if ($issueKey && ($_issue = Issue::byKey($issueKey))) {
                \Yii::$app->issue->attributes = $_issue->attributes;
            }
        }

        $this->telegram->handle();
    }

}
