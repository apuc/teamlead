<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property string $id Unique identifier for this chat
 * @property string $type Type of chat, can be either private, group, supergroup or channel
 * @property string $title Title, for supergroups, channels and group chats
 * @property string $username Username, for private chats, supergroups and channels if available
 * @property string $first_name First name of the other party in a private chat
 * @property string $last_name Last name of the other party in a private chat
 * @property int $all_members_are_administrators True if a all members of this group are admins
 * @property string $created_at Entry date creation
 * @property string $updated_at Entry date update
 * @property string $old_id Unique chat identifier, this is filled when a group is converted to a supergroup
 *
 * @property Conversation[] $conversations
 * @property EditedMessage[] $editedMessages
 * @property Message[] $messages
 * @property Message[] $messages0
 * @property Session[] $sessions
 * @property UserChat[] $userChats
 * @property User[] $users
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
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
            [['id', 'type'], 'required'],
            [['id', 'all_members_are_administrators', 'old_id'], 'integer'],
            [['type'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'username', 'first_name', 'last_name'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'title' => 'Title',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'all_members_are_administrators' => 'All Members Are Administrators',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'old_id' => 'Old ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(Conversation::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditedMessages()
    {
        return $this->hasMany(EditedMessage::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Message::className(), ['forward_from_chat' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSessions()
    {
        return $this->hasMany(Session::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserChats()
    {
        return $this->hasMany(UserChat::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_chat', ['chat_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\ChatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\ChatQuery(get_called_class());
    }
}
