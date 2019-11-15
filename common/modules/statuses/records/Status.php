<?php

namespace common\modules\statuses\records;

use common\modules\issues\records\Issue;
use Yii;

/**
 * This is the model class for table "jira_statuses".
 *
 * @property int $id
 * @property int $jira_id
 * @property string $name
 * @property string $descr
 * @property string $category
 * @property int $order_by
 * @property string $created_at
 * @property string $updated_at
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_statuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jira_id', 'name'], 'required'],
            [['jira_id', 'order_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['descr', 'category'], 'string', 'max' => 120],
            [['jira_id'], 'unique'],
            [['name'], 'unique'],
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
            'category' => 'Category',
            'order_by' => 'Order By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * @return \common\modules\statuses\queries\StatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\statuses\queries\StatusQuery(get_called_class());
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
