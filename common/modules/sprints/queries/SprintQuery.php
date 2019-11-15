<?php

namespace common\modules\sprints\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\sprints\records\Sprint]].
 *
 * @see \common\modules\sprints\records\Sprint
 */
class SprintQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\sprints\records\Sprint[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\sprints\records\Sprint|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
