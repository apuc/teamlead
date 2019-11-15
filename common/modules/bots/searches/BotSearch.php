<?php

namespace common\modules\bots\searches;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\bots\records\Bot;

/**
 * BotSearch represents the model behind the search form about `common\modules\bots\records\Bot`.
 */
class BotSearch extends Bot
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'token', 'hook'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Bot::find();

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
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'hook', $this->hook]);

        return $dataProvider;
    }
}