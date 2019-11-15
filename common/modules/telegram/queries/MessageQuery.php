<?php

namespace common\modules\telegram\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\telegram\records\Message]].
 *
 * @see \common\modules\telegram\records\Message
 */
class MessageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\records\Message[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\records\Message|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
