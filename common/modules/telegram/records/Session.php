<?php

namespace common\modules\telegram\records;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "session".
 *
 * @property string $id Unique identifier for this entry
 * @property string $chat_id Unique user or chat identifier
 * @property string $data Data stored from command
 * @property string $created_at Entry date creation
 * @property string $updated_at Entry date update
 *
 * @property Chat $chat
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'session';
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
            [['chat_id'], 'integer'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
            'chat_id' => 'Chat ID',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getValue($value)
    {
        $array = json_decode($this->data, true);
        return ArrayHelper::getValue($array, $value, null);
    }

    public function setValue($offset, $value)
    {
        $array = json_decode($this->data, true);
        ArrayHelper::setValue($array, $offset, $value);
        $this->save();
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
     * @return \common\modules\telegram\queries\SessionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\telegram\queries\SessionQuery(get_called_class());
    }

    public static function byChatId($chat_id)
    {
        return static::find()->where(compact('chat_id'))->one();
    }
}
