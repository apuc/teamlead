<?php

namespace common\modules\priorities\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\priorities\records\Priority]].
 *
 * @see \common\modules\priorities\records\Priority
 */
class PriorityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\priorities\records\Priority[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\priorities\records\Priority|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
