<?php

namespace common\modules\statuses\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\statuses\records\Status]].
 *
 * @see \common\modules\statuses\records\Status
 */
class StatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\statuses\records\Status[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\statuses\records\Status|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
