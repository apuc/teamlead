<?php

namespace common\modules\users\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\users\records\User;

/**
 * UserSearch represents the model behind the search form about `common\modules\users\records\User`.
 */
class UserSearch extends User
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'key', 'is_active'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'is_active', $this->is_active]);

        return $dataProvider;
    }
}
