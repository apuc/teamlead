<?php
namespace common\modules\issues\services;

use common\modules\issues\records\Issue;
use common\modules\priorities\services\PriorityService;
use common\modules\statuses\services\StatusService;
use common\modules\users\services\UserService;
use yii\helpers\ArrayHelper;

class IssueService
{

    public static function make(array $array)
    {
        $issue = Issue::byKey($array['key']) ?? new Issue();

        $fields = $array['fields'];

        $issue->key        = ArrayHelper::getValue($array, 'key');
        $issue->project    = ArrayHelper::getValue($fields, 'project.key');

        $issue->type       = ArrayHelper::getValue($fields, 'issuetype.name');
        $issue->summary    = ArrayHelper::getValue($fields, 'summary');
        $issue->parent     = ArrayHelper::getValue($fields, 'parent.key', '');
        $issue->duedate    = ArrayHelper::getValue($fields, 'duedate', null);

        $issue->estimate   = $fields['timeoriginalestimate'] ?: ($fields['timeestimate'] ?: '');
        $issue->storypoint = $fields[\Yii::$app->params['storypoint']];

        $issue->labels     = json_encode($fields['labels']);
        $issue->versions   = json_encode(ArrayHelper::getColumn($fields['fixVersions'], 'name'));
        $issue->sprint     = json_encode($fields['sprint'] ?: []);
        $issue->components = json_encode($fields['components']);
        $issue->json       = json_encode($array);

        $issue->status_id   = (StatusService::make($fields['status']))->id;
        $issue->priority_id = (PriorityService::make($fields['priority']))->id;

        $issue->assignee_id = empty($fields['assignee'])? null : (UserService::make($fields['assignee']))->id;
        $issue->author_id   = empty($fields['creator'])?  null : (UserService::make($fields['creator']))->id;

        $issue->save(false);

        return $issue;

    }

}