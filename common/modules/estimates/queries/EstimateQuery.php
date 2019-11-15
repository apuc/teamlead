<?php

namespace common\modules\estimates\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\estimates\records\Estimate]].
 *
 * @see \common\modules\estimates\records\Estimate
 */
class EstimateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\estimates\records\Estimate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\estimates\records\Estimate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
