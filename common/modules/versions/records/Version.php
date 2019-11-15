<?php

namespace common\modules\versions\records;

use Yii;

/**
 * This is the model class for table "jira_fix_versions".
 *
 * @property int $id
 * @property double $code
 * @property string $descr
 * @property int $order_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $enabled
 */
class Version extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_fix_versions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'number'],
            [['order_by', 'enabled'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['descr'], 'string', 'max' => 120],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'descr' => 'Descr',
            'order_by' => 'Order By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'enabled' => 'Enabled',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\versions\queries\VersionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\versions\queries\VersionQuery(get_called_class());
    }

    public static function byCode($code)
    {
        return static::find()->where(compact('code'))->one();
    }
}
