<?php

namespace common\modules\telegram\records;

use Yii;

/**
 * This is the model class for table "inline_query".
 *
 * @property string $id Unique identifier for this query
 * @property string $user_id Unique user identifier
 * @property string $location Location of the user
 * @property string $query Text of the query
 * @property string $offset Offset of the result
 * @property string $created_at Entry date creation
 *
 * @property User $user
 * @property TelegramUpdate[] $telegramUpdates
 */
class InlineQuery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inline_query';
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
            [['id', 'query'], 'required'],
            [['id', 'user_id'], 'integer'],
            [['query'], 'string'],
            [['created_at'], 'safe'],
            [['location', 'offset'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
            'user_id' => 'User ID',
            'location' => 'Location',
            'query' => 'Query',
            'offset' => 'Offset',
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
        return $this->hasMany(TelegramUpdate::className(), ['inline_query_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\telegram\queries\InlineQueryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\InlineQueryQuery(get_called_class());
    }
}
