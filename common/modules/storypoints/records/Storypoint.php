<?php

namespace common\modules\storypoints\records;

use Yii;

/**
 * This is the model class for table "jira_storypoints".
 *
 * @property int $id
 * @property string $value
 * @property string $name
 * @property string $descr
 * @property int $order_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $enabled
 */
class Storypoint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_storypoints';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'name'], 'required'],
            [['value', 'order_by', 'enabled'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['descr'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'name' => 'Name',
            'descr' => 'Descr',
            'order_by' => 'Order By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'enabled' => 'Enabled',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\storypoints\queries\StorypointQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\storypoints\queries\StorypointQuery(get_called_class());
    }
}
