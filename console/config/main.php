<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'common\BootstrapDependencies'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
        'migrate' => [ // Fixture generation command line.
            'class' => 'yii\console\controllers\MigrateController',
            'db' => 'db',
            'templateFile' => '@console/views/migration_db.php',
            'migrationPath' => '@console/migrations/db/'
        ],
        'migrate-tlg' => [ // Fixture generation command line.
            'class' => 'yii\console\controllers\MigrateController',
            'db' => 'db_tlg',
            'templateFile' => '@console/views/migration_db_tlg.php',
            'migrationPath' => '@console/migrations/db_tlg/'
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
