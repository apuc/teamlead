<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "edited_message".
 *
 * @property string $id Unique identifier for this entry
 * @property string $chat_id Unique chat identifier
 * @property string $message_id Unique message identifier
 * @property string $user_id Unique user identifier
 * @property string $edit_date Date the message was edited in timestamp format
 * @property string $text For text messages, the actual UTF-8 text of the message max message length 4096 char utf8
 * @property string $entities For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
 * @property string $caption For message with caption, the actual UTF-8 text of the caption
 *
 * @property Chat $chat
 * @property Message $chat0
 * @property User $user
 * @property TelegramUpdate[] $telegramUpdates
 * @property TelegramUpdate[] $telegramUpdates0
 */
class EditedMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edited_message';
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
            [['chat_id', 'message_id', 'user_id'], 'integer'],
            [['edit_date'], 'safe'],
            [['text', 'entities', 'caption'], 'string'],
            [['chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['chat_id' => 'id']],
            [['chat_id', 'message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Message::className(), 'targetAttribute' => ['chat_id' => 'chat_id', 'message_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'message_id' => 'Message ID',
            'user_id' => 'User ID',
            'edit_date' => 'Edit Date',
            'text' => 'Text',
            'entities' => 'Entities',
            'caption' => 'Caption',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::className(), ['id' => 'chat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat0()
    {
        return $this->hasOne(Message::className(), ['chat_id' => 'chat_id', 'id' => 'message_id']);
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
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::className(), ['edited_message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramUpdates0()
    {
        return $this->hasMany(TelegramUpdate::className(), ['edited_channel_post_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\EditedMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\EditedMessageQuery(get_called_class());
    }
}
