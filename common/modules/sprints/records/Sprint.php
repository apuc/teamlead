<?php

namespace common\modules\sprints\records;

use Yii;

/**
 * This is the model class for table "jira_sprints".
 *
 * @property int $id
 * @property int $jira_id
 * @property string $state
 * @property string $name
 * @property string $start
 * @property string $end
 * @property int $board_id
 */
class Sprint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_sprints';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jira_id', 'state', 'name'], 'required'],
            [['jira_id', 'board_id'], 'integer'],
            [['start', 'end'], 'safe'],
            [['state'], 'string', 'max' => 30],
            [['name'], 'string', 'max' => 120],
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
            'state' => 'State',
            'name' => 'Name',
            'start' => 'Start',
            'end' => 'End',
            'board_id' => 'Board ID',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\sprints\queries\SprintQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\sprints\queries\SprintQuery(get_called_class());
    }

    public static function byJiraId($jira_id)
    {
        return static::find()->where(['jira_id' => $jira_id])->one();
    }
}
