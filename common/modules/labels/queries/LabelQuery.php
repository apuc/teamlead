<?php

namespace common\modules\labels\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\labels\records\Label]].
 *
 * @see \common\modules\labels\records\Label
 */
class LabelQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\labels\records\Label[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\labels\records\Label|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
