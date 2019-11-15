<?php

namespace common\modules\bots\records;

use Yii;

/**
 * This is the model class for table "bots".
 *
 * @property int $id
 * @property string $name
 * @property string $token
 * @property string $hook
 */
class Bot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bots';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'token'], 'required'],
            [['name'], 'string', 'max' => 80],
            [['token'], 'string', 'max' => 60],
            [['hook'], 'string', 'max' => 180],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'token' => 'Token',
            'hook' => 'Hook',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\bots\queries\BotQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\bots\queries\BotQuery(get_called_class());
    }
}
