<?php

namespace common\modules\priorities\records;

use common\modules\issues\records\Issue;
use Yii;

/**
 * This is the model class for table "jira_priorities".
 *
 * @property int $id
 * @property int $jira_id
 * @property string $name
 * @property string $descr
 * @property int $order_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $enabled
 */
class Priority extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_priorities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jira_id', 'name'], 'required'],
            [['order_by', 'enabled', 'jira_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['descr'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jira_id' => 'Jira ID',
            'name' => 'Name',
            'descr' => 'Descr',
            'order_by' => 'Order By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'enabled' => 'Enabled',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['status_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\priorities\queries\PriorityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\priorities\queries\PriorityQuery(get_called_class());
    }

    public static function byJiraId($jira_id)
    {
        return static::find()->where(compact('jira_id'))->one();
    }

    public static function byName($name)
    {
        return static::find()->where(compact('name'))->one();
    }
}
