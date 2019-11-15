<?php

namespace common\modules\logs\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\logs\records\Log]].
 *
 * @see \common\modules\logs\records\Log
 */
class LogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\logs\records\Log[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\logs\records\Log|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
