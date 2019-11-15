<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id Unique identifier for this user or bot
 * @property int $is_bot True, if this user is a bot
 * @property string $first_name User's or bot's first name
 * @property string $last_name User's or bot's last name
 * @property string $username User's or bot's username
 * @property string $language_code IETF language tag of the user's language
 * @property string $created_at Entry date creation
 * @property string $updated_at Entry date update
 *
 * @property CallbackQuery[] $callbackQueries
 * @property ChosenInlineResult[] $chosenInlineResults
 * @property Conversation[] $conversations
 * @property EditedMessage[] $editedMessages
 * @property InlineQuery[] $inlineQueries
 * @property Message[] $messages
 * @property Message[] $messages0
 * @property Message[] $messages1
 * @property Message[] $messages2
 * @property PreCheckoutQuery[] $preCheckoutQueries
 * @property ShippingQuery[] $shippingQueries
 * @property UserChat[] $userChats
 * @property Chat[] $chats
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
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
            [['id', 'is_bot'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 191],
            [['language_code'], 'string', 'max' => 10],
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
            'is_bot' => 'Is Bot',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'username' => 'Username',
            'language_code' => 'Language Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallbackQueries()
    {
        return $this->hasMany(CallbackQuery::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChosenInlineResults()
    {
        return $this->hasMany(ChosenInlineResult::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(Conversation::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditedMessages()
    {
        return $this->hasMany(EditedMessage::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInlineQueries()
    {
        return $this->hasMany(InlineQuery::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Message::className(), ['forward_from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages1()
    {
        return $this->hasMany(Message::className(), ['forward_from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages2()
    {
        return $this->hasMany(Message::className(), ['left_chat_member' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreCheckoutQueries()
    {
        return $this->hasMany(PreCheckoutQuery::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShippingQueries()
    {
        return $this->hasMany(ShippingQuery::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserChats()
    {
        return $this->hasMany(UserChat::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['id' => 'chat_id'])->viaTable('user_chat', ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\UserQuery(get_called_class());
    }
}
