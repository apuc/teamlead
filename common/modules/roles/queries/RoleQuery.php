<?php

namespace common\modules\roles\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\roles\records\Role]].
 *
 * @see \common\modules\roles\records\Role
 */
class RoleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\roles\records\Role[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\roles\records\Role|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
