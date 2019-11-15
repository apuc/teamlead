<?php
namespace common;

use common\modules\labels\services\LabelService;
use common\modules\priorities\services\PriorityService;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\di\ServiceLocator;


class BootstrapDependencies implements BootstrapInterface
{
    /**
     * @param Application $app
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function bootstrap($app)
    {

        \Yii::$container->setSingleton(\Longman\TelegramBot\Telegram::class, function () use ($app) {
            $telegram = new \Longman\TelegramBot\Telegram(\Yii::$app->params['telegramToken'], \Yii::$app->params['telegramBotName']);
            $telegram->addCommandsPaths([\Yii::getAlias('@commands')]);
            $telegram->enableAdmin(343490789);

            $mysql_credentials = [
                'host'     => 'localhost',
                'port'     => 3306,
                'user'     => 'root',
                'password' => '',
                'database' => 'tlg_teamlead',
            ];

            $telegram->enableMySql($mysql_credentials);
            return $telegram;
         });
    }
}