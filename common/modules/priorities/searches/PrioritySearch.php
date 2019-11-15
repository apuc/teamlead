<?php

namespace common\modules\priorities\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\priorities\records\Priority;

/**
 * PrioritySearch represents the model behind the search form about `common\modules\priorities\records\Priority`.
 */
class PrioritySearch extends Priority
{
    public function rules()
    {
        return [
            [['id', 'order_by', 'jira_id'], 'integer'],
            [['name', 'descr', 'created_at', 'updated_at', 'enabled'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Priority::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jira_id' => $this->jira_id,
            'order_by' => $this->order_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'descr', $this->descr])
            ->andFilterWhere(['like', 'enabled', $this->enabled]);

        return $dataProvider;
    }
}
