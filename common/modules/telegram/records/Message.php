<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property string $chat_id
 * @property string $id
 * @property string $user_id
 * @property string $date
 * @property string $forward_from
 * @property string $forward_from_chat
 * @property string $forward_from_message_id
 * @property string $forward_signature
 * @property string $forward_sender_name
 * @property string $forward_date
 * @property string $reply_to_chat
 * @property string $reply_to_message
 * @property string $edit_date
 * @property string $media_group_id
 * @property string $author_signature
 * @property string $text
 * @property string $entities
 * @property string $caption_entities
 * @property string $audio
 * @property string $document
 * @property string $animation
 * @property string $game
 * @property string $photo
 * @property string $sticker
 * @property string $video
 * @property string $voice
 * @property string $video_note
 * @property string $caption
 * @property string $contact
 * @property string $location
 * @property string $venue
 * @property string $poll
 * @property string $new_chat_members
 * @property string $left_chat_member
 * @property string $new_chat_title
 * @property string $new_chat_photo
 * @property int $delete_chat_photo
 * @property int $group_chat_created
 * @property int $supergroup_chat_created
 * @property int $channel_chat_created
 * @property string $migrate_to_chat_id
 * @property string $migrate_from_chat_id
 * @property string $pinned_message
 * @property string $invoice
 * @property string $successful_payment
 * @property string $connected_website
 * @property string $passport_data
 * @property string $reply_markup
 *
 * @property CallbackQuery[] $callbackQueries
 * @property EditedMessage[] $editedMessages
 * @property User $user
 * @property Chat $chat
 * @property User $forwardFrom
 * @property Chat $forwardFromChat
 * @property Message $replyToChat
 * @property Message[] $messages
 * @property User $forwardFrom0
 * @property User $leftChatMember
 * @property TelegramUpdate[] $telegramUpdates
 * @property TelegramUpdate[] $telegramUpdates0
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
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
            [['chat_id', 'id'], 'required'],
            [['chat_id', 'id', 'user_id', 'forward_from', 'forward_from_chat', 'forward_from_message_id', 'reply_to_chat', 'reply_to_message', 'edit_date', 'left_chat_member', 'delete_chat_photo', 'group_chat_created', 'supergroup_chat_created', 'channel_chat_created', 'migrate_to_chat_id', 'migrate_from_chat_id'], 'integer'],
            [['date', 'forward_date'], 'safe'],
            [['forward_signature', 'forward_sender_name', 'media_group_id', 'author_signature', 'text', 'entities', 'caption_entities', 'audio', 'document', 'animation', 'game', 'photo', 'sticker', 'video', 'voice', 'video_note', 'caption', 'contact', 'location', 'venue', 'poll', 'new_chat_members', 'new_chat_photo', 'pinned_message', 'invoice', 'successful_payment', 'connected_website', 'passport_data', 'reply_markup'], 'string'],
            [['new_chat_title'], 'string', 'max' => 255],
            [['chat_id', 'id'], 'unique', 'targetAttribute' => ['chat_id', 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['chat_id' => 'id']],
            [['forward_from'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['forward_from' => 'id']],
            [['forward_from_chat'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['forward_from_chat' => 'id']],
            [['reply_to_chat', 'reply_to_message'], 'exist', 'skipOnError' => true, 'targetClass' => Message::className(), 'targetAttribute' => ['reply_to_chat' => 'chat_id', 'reply_to_message' => 'id']],
            [['forward_from'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['forward_from' => 'id']],
            [['left_chat_member'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['left_chat_member' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'chat_id' => 'Chat ID',
            'id' => 'ID',
            'user_id' => 'User ID',
            'date' => 'Date',
            'forward_from' => 'Forward From',
            'forward_from_chat' => 'Forward From Chat',
            'forward_from_message_id' => 'Forward From Message ID',
            'forward_signature' => 'Forward Signature',
            'forward_sender_name' => 'Forward Sender Name',
            'forward_date' => 'Forward Date',
            'reply_to_chat' => 'Reply To Chat',
            'reply_to_message' => 'Reply To Message',
            'edit_date' => 'Edit Date',
            'media_group_id' => 'Media Group ID',
            'author_signature' => 'Author Signature',
            'text' => 'Text',
            'entities' => 'Entities',
            'caption_entities' => 'Caption Entities',
            'audio' => 'Audio',
            'document' => 'Document',
            'animation' => 'Animation',
            'game' => 'Game',
            'photo' => 'Photo',
            'sticker' => 'Sticker',
            'video' => 'Video',
            'voice' => 'Voice',
            'video_note' => 'Video Note',
            'caption' => 'Caption',
            'contact' => 'Contact',
            'location' => 'Location',
            'venue' => 'Venue',
            'poll' => 'Poll',
            'new_chat_members' => 'New Chat Members',
            'left_chat_member' => 'Left Chat Member',
            'new_chat_title' => 'New Chat Title',
            'new_chat_photo' => 'New Chat Photo',
            'delete_chat_photo' => 'Delete Chat Photo',
            'group_chat_created' => 'Group Chat Created',
            'supergroup_chat_created' => 'Supergroup Chat Created',
            'channel_chat_created' => 'Channel Chat Created',
            'migrate_to_chat_id' => 'Migrate To Chat ID',
            'migrate_from_chat_id' => 'Migrate From Chat ID',
            'pinned_message' => 'Pinned Message',
            'invoice' => 'Invoice',
            'successful_payment' => 'Successful Payment',
            'connected_website' => 'Connected Website',
            'passport_data' => 'Passport Data',
            'reply_markup' => 'Reply Markup',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallbackQueries()
    {
        return $this->hasMany(CallbackQuery::className(), ['chat_id' => 'chat_id', 'message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditedMessages()
    {
        return $this->hasMany(EditedMessage::className(), ['chat_id' => 'chat_id', 'message_id' => 'id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getForwardFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'forward_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForwardFromChat()
    {
        return $this->hasOne(Chat::className(), ['id' => 'forward_from_chat']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplyToChat()
    {
        return $this->hasOne(Message::className(), ['chat_id' => 'reply_to_chat', 'id' => 'reply_to_message']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['reply_to_chat' => 'chat_id', 'reply_to_message' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForwardFrom0()
    {
        return $this->hasOne(User::className(), ['id' => 'forward_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeftChatMember()
    {
        return $this->hasOne(User::className(), ['id' => 'left_chat_member']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::className(), ['chat_id' => 'chat_id', 'message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramUpdates0()
    {
        return $this->hasMany(TelegramUpdate::className(), ['chat_id' => 'chat_id', 'channel_post_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\MessageQuery(get_called_class());
    }
}
