<?php

namespace common\modules\posts\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\posts\records\Post]].
 *
 * @see \common\modules\posts\records\Post
 */
class PostQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\posts\records\Post[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\posts\records\Post|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
