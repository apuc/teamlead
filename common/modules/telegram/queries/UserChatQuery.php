<?php

namespace common\modules\telegram\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\telegram\records\UserChat]].
 *
 * @see \common\modules\telegram\records\UserChat
 */
class UserChatQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\records\UserChat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\records\UserChat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
