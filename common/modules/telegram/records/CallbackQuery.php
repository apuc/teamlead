<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "callback_query".
 *
 * @property string $id Unique identifier for this query
 * @property string $user_id Unique user identifier
 * @property string $chat_id Unique chat identifier
 * @property string $message_id Unique message identifier
 * @property string $inline_message_id Identifier of the message sent via the bot in inline mode, that originated the query
 * @property string $chat_instance Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent
 * @property string $data Data associated with the callback button
 * @property string $game_short_name Short name of a Game to be returned, serves as the unique identifier for the game
 * @property string $created_at Entry date creation
 *
 * @property User $user
 * @property Message $chat
 * @property TelegramUpdate[] $telegramUpdates
 */
class CallbackQuery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'callback_query';
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
            [['id'], 'required'],
            [['id', 'user_id', 'chat_id', 'message_id'], 'integer'],
            [['created_at'], 'safe'],
            [['inline_message_id', 'chat_instance', 'data', 'game_short_name'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['chat_id', 'message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Message::className(), 'targetAttribute' => ['chat_id' => 'chat_id', 'message_id' => 'id']],
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
            'message_id' => 'Message ID',
            'inline_message_id' => 'Inline Message ID',
            'chat_instance' => 'Chat Instance',
            'data' => 'Data',
            'game_short_name' => 'Game Short Name',
            'created_at' => 'Created At',
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
        return $this->hasOne(Message::className(), ['chat_id' => 'chat_id', 'id' => 'message_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::className(), ['callback_query_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\CallbackQueryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\CallbackQueryQuery(get_called_class());
    }
}
