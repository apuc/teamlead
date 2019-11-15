<?php

namespace common\modules\sprints\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\sprints\records\Sprint;

/**
 * SprintSearch represents the model behind the search form about `common\modules\sprints\records\Sprint`.
 */
class SprintSearch extends Sprint
{
    public function rules()
    {
        return [
            [['id', 'jira_id', 'board_id'], 'integer'],
            [['state', 'name', 'start', 'end'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Sprint::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jira_id' => $this->jira_id,
            'start' => $this->start,
            'end' => $this->end,
            'board_id' => $this->board_id,
        ]);

        $query->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
