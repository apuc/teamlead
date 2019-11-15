<?php

namespace common\modules\labels\records;

use Yii;

/**
 * This is the model class for table "jira_labels".
 *
 * @property int $id
 * @property string $name
 * @property string $descr
 * @property int $order_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $enabled
 */
class Label extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_labels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['order_by', 'enabled'], 'integer'],
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
     * @return \common\modules\labels\queries\LabelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\labels\queries\LabelQuery(get_called_class());
    }

    public static function byName($name)
    {
        return static::find()->where(['=', 'LCASE(name)', strtolower($name)])->one();
    }
}
