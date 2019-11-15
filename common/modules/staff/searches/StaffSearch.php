<?php

namespace common\modules\staff\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\staff\records\Staff;

/**
 * StaffSearch represents the model behind the search form about `common\modules\staff\records\Staff`.
 */
class StaffSearch extends Staff
{
    public function rules()
    {
        return [
            [['id', 'jira_user_id', 'tlg_user_id', 'role_id', 'post_id'], 'integer'],
            [['name', 'email', 'phone'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Staff::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'jira_user_id' => $this->jira_user_id,
            'tlg_user_id' => $this->tlg_user_id,
            'role_id' => $this->role_id,
            'post_id' => $this->post_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
