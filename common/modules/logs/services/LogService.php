<?php
namespace common\modules\logs\services;


use common\modules\logs\records\Log;

class LogService
{

    public static function make(array $data)
    {
        $model = new Log();
        $model->key = '';
        $model->last_act = '';
        $model->json = '';

        $model->save();
    }


}