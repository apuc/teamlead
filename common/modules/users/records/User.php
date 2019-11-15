<?php

namespace common\modules\users\records;

use common\modules\issues\records\Issue;
use Yii;

/**
 * This is the model class for table "jira_users".
 *
 * @property int $id
 * @property string $name
 * @property string $key
 * @property int $is_active
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'key'], 'required'],
            [['is_active'], 'integer'],
            [['name', 'key'], 'string', 'max' => 80],
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
            'key' => 'Key',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['status_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\users\queries\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\users\queries\UserQuery(get_called_class());
    }

    public static function byKey($key)
    {
        return static::find()->where(compact('key'))->one();
    }

    public static function byName($name)
    {
        return static::find()->where(compact('name'))->one();
    }
}
