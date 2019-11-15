<?php

namespace common\modules\estimates\records;

use Yii;

/**
 * This is the model class for table "jira_estimates".
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
class Estimate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_estimates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'name'], 'required'],
            [['order_by', 'enabled'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['value', 'name'], 'string', 'max' => 64],
            [['descr'], 'string', 'max' => 120],
            [['value'], 'unique'],
            [['name'], 'unique'],
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
     * @return \common\modules\estimates\queries\EstimateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\estimates\queries\EstimateQuery(get_called_class());
    }
}
