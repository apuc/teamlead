<?php

namespace common\modules\labels\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\labels\records\Label;

/**
 * LabelSearch represents the model behind the search form about `common\modules\labels\records\Label`.
 */
class LabelSearch extends Label
{
    public function rules()
    {
        return [
            [['id', 'order_by'], 'integer'],
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
        $query = Label::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
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
