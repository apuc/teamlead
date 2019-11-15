<?php
namespace frontend\controllers;

use Longman\TelegramBot\Telegram;
use yii\base\Module;


class JiraController extends \yii\web\Controller
{
    /**
     * @var Telegram
     */
    private $telegram;

    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        Telegram $telegram
    ) {
        parent::__construct($id, $module, $config);
        $this->telegram = $telegram;
    }

    public function actionHook()
    {

    }


    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

}
