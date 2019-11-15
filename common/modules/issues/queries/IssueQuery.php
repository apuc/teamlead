<?php

namespace common\modules\issues\queries;

use common\modules\staff\records\Staff;

/**
 * This is the ActiveQuery class for [[\common\modules\issues\records\Issue]].
 *
 * @see \common\modules\issues\records\Issue
 */
class IssueQuery extends \yii\db\ActiveQuery
{
    public function byJiraUser(Staff $staff)
    {
        return $this->andWhere(['=', 'assignee_id', $staff->jira_user_id]);
    }

    public function actual()
    {

    }

    /**
     * {@inheritdoc}
     * @return \common\modules\issues\records\Issue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\issues\records\Issue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
