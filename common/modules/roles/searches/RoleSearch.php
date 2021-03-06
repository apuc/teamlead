<?php

namespace common\modules\roles\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\roles\records\Role;

/**
 * RoleSearch represents the model behind the search form about `common\modules\roles\records\Role`.
 */
class RoleSearch extends Role
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'descr', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Role::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'descr', $this->descr]);

        return $dataProvider;
    }
}
