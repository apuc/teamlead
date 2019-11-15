<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "chosen_inline_result".
 *
 * @property string $id Unique identifier for this entry
 * @property string $result_id The unique identifier for the result that was chosen
 * @property string $user_id The user that chose the result
 * @property string $location Sender location, only for bots that require user location
 * @property string $inline_message_id Identifier of the sent inline message
 * @property string $query The query that was used to obtain the result
 * @property string $created_at Entry date creation
 *
 * @property User $user
 * @property TelegramUpdate[] $telegramUpdates
 */
class ChosenInlineResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chosen_inline_result';
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
            [['user_id'], 'integer'],
            [['query'], 'required'],
            [['query'], 'string'],
            [['created_at'], 'safe'],
            [['result_id', 'location', 'inline_message_id'], 'string', 'max' => 255],
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
            'result_id' => 'Result ID',
            'user_id' => 'User ID',
            'location' => 'Location',
            'inline_message_id' => 'Inline Message ID',
            'query' => 'Query',
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
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::className(), ['chosen_inline_result_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\ChosenInlineResultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\ChosenInlineResultQuery(get_called_class());
    }
}
