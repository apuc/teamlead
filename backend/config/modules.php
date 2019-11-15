<?php

return [
    'bots'       => 'backend\modules\bots\Bot',
    'sprints'    => 'backend\modules\sprints\Sprint',
    'labels'     => 'backend\modules\labels\Label',
    'priorities' => 'backend\modules\priorities\Priority',
    'statuses'   => 'backend\modules\statuses\Status',
    'users'      => 'backend\modules\users\User',
    'posts'      => 'backend\modules\posts\Post',
    'roles'      => 'backend\modules\roles\Role',
    'telegram'   => 'backend\modules\telegram\Telegram',
    'staff'      => 'backend\modules\staff\Staff',

    'gii' => [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'],
        'generators' => [
            'kartikCrud' => [
                'class' => 'warrence\kartikgii\crud\Generator',
            ]
        ],
    ],
    'gridview' => ['class' => 'kartik\grid\Module'],
];