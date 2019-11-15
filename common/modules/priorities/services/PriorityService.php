<?php
namespace common\modules\priorities\services;

use common\modules\priorities\records\Priority;

class PriorityService
{
    public static function getPriorityNames()
    {
        return array_map(function (Priority $priority) {
            return $priority->name;
        }, Priority::find()->all());
    }

    public static function make(array $array)
    {
        $model = Priority::byJiraId($array['id']) ?? new Priority();

        $model->jira_id = $array['id'];
        $model->name    = $array['name'];
        $model->descr   = $array['description'] ?? '';
        $model->jira_id = $array['id'];

        $model->save();
        return $model;
    }

}