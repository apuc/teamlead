<?php

namespace common\modules\users\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\users\records\User]].
 *
 * @see \common\modules\users\records\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\users\records\User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\users\records\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
