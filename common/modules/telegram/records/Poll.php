<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "poll".
 *
 * @property string $id Unique poll identifier
 * @property string $question Poll question
 * @property string $options List of poll options
 * @property int $is_closed True, if the poll is closed
 * @property string $created_at Entry date creation
 *
 * @property TelegramUpdate[] $telegramUpdates
 */
class Poll extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'poll';
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
            [['id', 'question', 'options'], 'required'],
            [['id', 'is_closed'], 'integer'],
            [['options'], 'string'],
            [['created_at'], 'safe'],
            [['question'], 'string', 'max' => 255],
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
            'question' => 'Question',
            'options' => 'Options',
            'is_closed' => 'Is Closed',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelegramUpdates()
    {
        return $this->hasMany(TelegramUpdate::className(), ['poll_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\PollQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\PollQuery(get_called_class());
    }
}
