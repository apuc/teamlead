<?php

namespace common\modules\telegram\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\telegram\records\User]].
 *
 * @see \common\modules\telegram\records\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\records\User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\records\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
