<?php

namespace common\modules\logs\records;

use Yii;

/**
 * This is the model class for table "jira_log".
 *
 * @property int $id
 * @property string $key
 * @property string $last_act
 * @property string $json
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['json'], 'string'],
            [['key'], 'string', 'max' => 32],
            [['last_act'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'last_act' => 'Last Act',
            'json' => 'Json',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\logs\queries\LogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\logs\queries\LogQuery(get_called_class());
    }
}
