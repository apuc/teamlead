<?php
namespace common\modules\statuses\services;

use common\modules\statuses\records\Status;

class StatusService
{
    public static function make(array $array)
    {
        $model = Status::byJiraId($array['id']) ?? new Status();

        $model->jira_id = $array['id'];
        $model->name    = $array['name'];
        $model->descr   = $array['description'] ?? '';
        $model->category= $array['statusCategory']['name'];

        $model->save(false);
        return $model;
    }

}