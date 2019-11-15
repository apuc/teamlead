<?php

namespace common\modules\versions\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\versions\records\Version]].
 *
 * @see \common\modules\versions\records\Version
 */
class VersionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\versions\records\Version[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\versions\records\Version|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
