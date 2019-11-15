<?php

namespace common\modules\storypoints\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\storypoints\records\Storypoint]].
 *
 * @see \common\modules\storypoints\records\Storypoint
 */
class StorypointQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\storypoints\records\Storypoint[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\storypoints\records\Storypoint|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
