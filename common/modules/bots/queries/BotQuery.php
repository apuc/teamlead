<?php

namespace common\modules\bots\queries;

/**
 * This is the ActiveQuery class for [[\common\modules\bots\records\Bot]].
 *
 * @see \common\modules\bots\records\Bot
 */
class BotQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\modules\bots\records\Bot[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\bots\records\Bot|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
