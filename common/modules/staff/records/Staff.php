<?php

namespace common\modules\staff\records;

use common\modules\posts\records\Post;
use common\modules\roles\records\Role;
use common\modules\users\records\User;
use Yii;

/**
 * This is the model class for table "staff".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int $jira_user_id
 * @property string $tlg_user_id
 * @property int $role_id
 * @property int $post_id
 *
 * @property User $jiraUser
 * @property Post $post
 * @property Role $role
 */
class Staff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['jira_user_id', 'tlg_user_id', 'role_id', 'post_id'], 'integer'],
            [['name', 'email'], 'string', 'max' => 120],
            [['phone'], 'string', 'max' => 20],
            [['jira_user_id'], 'unique'],
            [['tlg_user_id'], 'unique'],
            [['jira_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['jira_user_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
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
            'email' => 'Email',
            'phone' => 'Phone',
            'jira_user_id' => 'Jira User ID',
            'tlg_user_id' => 'Tlg User ID',
            'role_id' => 'Role ID',
            'post_id' => 'Post ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJiraUser()
    {
        return $this->hasOne(User::className(), ['id' => 'jira_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    public function isTeamlead()
    {
        return mb_strtolower($this->role->name) === 'тимлид';
    }

    public function isWorker()
    {
        return mb_strtolower($this->role->name) === 'сотрудник';
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\staff\queries\StaffQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\staff\queries\StaffQuery(get_called_class());
    }

    public static function byTlgUserId($tlg_user_id)
    {
        return static::find()->where(compact('tlg_user_id'))->one();
    }
}
