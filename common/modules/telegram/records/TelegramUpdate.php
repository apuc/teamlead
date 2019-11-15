<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "telegram_update".
 *
 * @property string $id Update's unique identifier
 * @property string $chat_id Unique chat identifier
 * @property string $message_id New incoming message of any kind - text, photo, sticker, etc.
 * @property string $edited_message_id New version of a message that is known to the bot and was edited
 * @property string $channel_post_id New incoming channel post of any kind - text, photo, sticker, etc.
 * @property string $edited_channel_post_id New version of a channel post that is known to the bot and was edited
 * @property string $inline_query_id New incoming inline query
 * @property string $chosen_inline_result_id The result of an inline query that was chosen by a user and sent to their chat partner
 * @property string $callback_query_id New incoming callback query
 * @property string $shipping_query_id New incoming shipping query. Only for invoices with flexible price
 * @property string $pre_checkout_query_id New incoming pre-checkout query. Contains full information about checkout
 * @property string $poll_id New poll state. Bots receive only updates about polls, which are sent or stopped by the bot
 *
 * @property Message $chat
 * @property Poll $poll
 * @property EditedMessage $editedMessage
 * @property Message $chat0
 * @property EditedMessage $editedChannelPost
 * @property InlineQuery $inlineQuery
 * @property ChosenInlineResult $chosenInlineResult
 * @property CallbackQuery $callbackQuery
 * @property ShippingQuery $shippingQuery
 * @property PreCheckoutQuery $preCheckoutQuery
 */
class TelegramUpdate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_update';
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
            [['id', 'chat_id', 'message_id', 'edited_message_id', 'channel_post_id', 'edited_channel_post_id', 'inline_query_id', 'chosen_inline_result_id', 'callback_query_id', 'shipping_query_id', 'pre_checkout_query_id', 'poll_id'], 'integer'],
            [['id'], 'unique'],
            [['chat_id', 'message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Message::className(), 'targetAttribute' => ['chat_id' => 'chat_id', 'message_id' => 'id']],
            [['poll_id'], 'exist', 'skipOnError' => true, 'targetClass' => Poll::className(), 'targetAttribute' => ['poll_id' => 'id']],
            [['edited_message_id'], 'exist', 'skipOnError' => true, 'targetClass' => EditedMessage::className(), 'targetAttribute' => ['edited_message_id' => 'id']],
            [['chat_id', 'channel_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Message::className(), 'targetAttribute' => ['chat_id' => 'chat_id', 'channel_post_id' => 'id']],
            [['edited_channel_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => EditedMessage::className(), 'targetAttribute' => ['edited_channel_post_id' => 'id']],
            [['inline_query_id'], 'exist', 'skipOnError' => true, 'targetClass' => InlineQuery::className(), 'targetAttribute' => ['inline_query_id' => 'id']],
            [['chosen_inline_result_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChosenInlineResult::className(), 'targetAttribute' => ['chosen_inline_result_id' => 'id']],
            [['callback_query_id'], 'exist', 'skipOnError' => true, 'targetClass' => CallbackQuery::className(), 'targetAttribute' => ['callback_query_id' => 'id']],
            [['shipping_query_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShippingQuery::className(), 'targetAttribute' => ['shipping_query_id' => 'id']],
            [['pre_checkout_query_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreCheckoutQuery::className(), 'targetAttribute' => ['pre_checkout_query_id' => 'id']],
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
            'edited_message_id' => 'Edited Message ID',
            'channel_post_id' => 'Channel Post ID',
            'edited_channel_post_id' => 'Edited Channel Post ID',
            'inline_query_id' => 'Inline Query ID',
            'chosen_inline_result_id' => 'Chosen Inline Result ID',
            'callback_query_id' => 'Callback Query ID',
            'shipping_query_id' => 'Shipping Query ID',
            'pre_checkout_query_id' => 'Pre Checkout Query ID',
            'poll_id' => 'Poll ID',
        ];
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
    public function getPoll()
    {
        return $this->hasOne(Poll::className(), ['id' => 'poll_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditedMessage()
    {
        return $this->hasOne(EditedMessage::className(), ['id' => 'edited_message_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat0()
    {
        return $this->hasOne(Message::className(), ['chat_id' => 'chat_id', 'id' => 'channel_post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditedChannelPost()
    {
        return $this->hasOne(EditedMessage::className(), ['id' => 'edited_channel_post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInlineQuery()
    {
        return $this->hasOne(InlineQuery::className(), ['id' => 'inline_query_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChosenInlineResult()
    {
        return $this->hasOne(ChosenInlineResult::className(), ['id' => 'chosen_inline_result_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallbackQuery()
    {
        return $this->hasOne(CallbackQuery::className(), ['id' => 'callback_query_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShippingQuery()
    {
        return $this->hasOne(ShippingQuery::className(), ['id' => 'shipping_query_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreCheckoutQuery()
    {
        return $this->hasOne(PreCheckoutQuery::className(), ['id' => 'pre_checkout_query_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\TelegramUpdateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\TelegramUpdateQuery(get_called_class());
    }
}
