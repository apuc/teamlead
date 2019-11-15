<?php

namespace common\modules\statuses\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\statuses\records\Status;

/**
 * StatusSearch represents the model behind the search form about `common\modules\statuses\records\Status`.
 */
class StatusSearch extends Status
{
    public function rules()
    {
        return [
            [['id', 'jira_id', 'order_by'], 'integer'],
            [['name', 'descr', 'category', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Status::find();

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
            ->andFilterWhere(['like', 'category', $this->category]);

        return $dataProvider;
    }
}
