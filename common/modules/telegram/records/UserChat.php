<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "user_chat".
 *
 * @property string $user_id Unique user identifier
 * @property string $chat_id Unique user or chat identifier
 *
 * @property User $user
 * @property Chat $chat
 */
class UserChat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_chat';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_tlg');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'chat_id'], 'required'],
            [['user_id', 'chat_id'], 'integer'],
            [['user_id', 'chat_id'], 'unique', 'targetAttribute' => ['user_id', 'chat_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['chat_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'chat_id' => 'Chat ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::className(), ['id' => 'chat_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\UserChatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\UserChatQuery(get_called_class());
    }
}
