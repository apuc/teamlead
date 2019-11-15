<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "conversation".
 *
 * @property string $id Unique identifier for this entry
 * @property string $user_id Unique user identifier
 * @property string $chat_id Unique user or chat identifier
 * @property string $status Conversation state
 * @property string $command Default command to execute
 * @property string $notes Data stored from command
 * @property string $created_at Entry date creation
 * @property string $updated_at Entry date update
 *
 * @property User $user
 * @property Chat $chat
 */
class Conversation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversation';
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
            [['user_id', 'chat_id'], 'integer'],
            [['status', 'notes'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['command'], 'string', 'max' => 160],
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
            'id' => 'ID',
            'user_id' => 'User ID',
            'chat_id' => 'Chat ID',
            'status' => 'Status',
            'command' => 'Command',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * @return \common\modules\telegram\queries\ConversationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\ConversationQuery(get_called_class());
    }
}
