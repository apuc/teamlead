<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'jira' => function () {
            return \App\JiraApi::server(\Yii::$app->params['jiraUrl'],\Yii::$app->params['jiraEmail'], \Yii::$app->params['jiraToken']);
        },
    ],
];
