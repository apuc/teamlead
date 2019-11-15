<?php

namespace common\modules\issues\records;

use common\modules\priorities\records\Priority;
use common\modules\statuses\records\Status;
use common\modules\users\records\User;
use Yii;

/**
 * This is the model class for table "jira_issues".
 *
 * @property int $id
 * @property int $assignee_id
 * @property int $author_id
 * @property string $key
 * @property string $parent
 * @property string $project
 * @property string $type
 * @property string $summary
 * @property string $description
 * @property int $priority_id
 * @property int $status_id
 * @property string $duedate
 * @property int $storypoint
 * @property string $estimate
 * @property string $labels
 * @property string $versions
 * @property string $sprint
 * @property string $components
 * @property string $last_act
 * @property string $json
 * @property int $is_deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $author
 * @property User $assignee
 * @property Status $status
 * @property Priority $priority
 */
class Issue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jira_issues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'project', 'type', 'status_id', 'priority_id'], 'required'],
            [['duedate', 'created_at', 'updated_at'], 'safe'],
            [['storypoint', 'author_id', 'assignee_id', 'status_id', 'priority_id', 'is_deleted'], 'integer'],
            [['sprint', 'components', 'json', 'description'], 'string'],
            [['key', 'parent'], 'string', 'max' => 32],
            [['project', 'type', 'last_act'], 'string', 'max' => 60],
            [['summary'], 'string', 'max' => 255],
            [['estimate'], 'string', 'max' => 12],
            [['labels', 'versions'], 'string', 'max' => 512],
            [['key'], 'unique'],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author' => 'id']],
            [['assignee'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['assignee' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status' => 'id']],
            [['priority'], 'exist', 'skipOnError' => true, 'targetClass' => Priority::className(), 'targetAttribute' => ['priority' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Ключ',
            'parent' => 'Родительская азадча',
            'project' => 'Проект',
            'type' => 'Тип задачи',
            'summary' => 'Тема',
            'description' => 'Описание',
            'priority_id' => 'Приоритет',
            'status_id' => 'Статус',
            'duedate' => 'Срок выполнения',
            'storypoint' => 'Историческая точка',
            'estimate' => 'Срок выполнения',
            'labels' => 'Метки',
            'versions' => 'Версии',
            'sprint' => 'Спринты',
            'components' => 'Компоненты',
            'author_id' => 'Автор',
            'assignee_id' => 'Исполнитель',
            'last_act' => 'Последнее действие',
            'json' => 'Json',
            'is_deleted' => 'Задача удалена',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getDetails()
    {
        $data = [
            '*Оценка*: '. (empty($this->estimate)? "\u{1F6AB}" : \App\Helpers::estimate2dhmFormat($this->estimate)),
            '*Storypoint*: ' . ($this->storypoint ?: "\u{1F6AB}"),
            '*Версия*: '. (implode(',',$this->getVersions()) ?: "\u{1F6AB}"),
            '*Метки*: '. (implode(',',$this->getLabels()) ?: "\u{1F6AB}"),

            '*Приоритет*: '.$this->priority,
            '*Статус*: '.$this->status,
            '*Исполнитель*: '.($this->assignee->name ?: "\u{1F6AB}"),
            '*Описание*:'."\n".($this->description ?: "\u{1F6AB}"),
        ];
        return implode("\n", $data);
    }

    public function getHref()
    {
        return \Yii::$app->params['jiraUrl'].'/browse/'.$this->key;
    }

    public function getLink()
    {
        $href = $this->getHref();
        return '['.$this->key.'.'.$this->summary.']('.$href.')';
    }

    public function getSprint() : array
    {
        return json_decode($this->sprint, true);
    }

    public function getLabels() : array
    {
        return json_decode($this->labels, true);
    }

    public function getComments() : array
    {
        $data = json_decode($this->json, true);
        return $data['fields']['comment']['comments'] ?? [];
    }

    public function getComponents() : array
    {
        return json_decode($this->components, true);
    }

    public function getVersions()
    {
        return json_decode($this->versions, true);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriority()
    {
        return $this->hasOne(Priority::className(), ['id' => 'priority_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignee()
    {
        return $this->hasOne(User::className(), ['id' => 'assignee_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\modules\issues\queries\IssueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\modules\issues\queries\IssueQuery(get_called_class());
    }

    public static function byKey($key)
    {
        return static::find()->where(compact('key'))->one();
    }
}
