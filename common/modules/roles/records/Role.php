<?php

namespace common\modules\roles\records;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string $name
 * @property string $descr
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Staff[] $staff
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['descr'], 'string', 'max' => 250],
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
            'name' => 'Name',
            'descr' => 'Descr',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasMany(Staff::className(), ['role_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\roles\queries\RoleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\roles\queries\RoleQuery(get_called_class());
    }
}
