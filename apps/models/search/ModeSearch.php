<?php

namespace search;

use models\Mode;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ModeSearch extends Mode
{
    public function rules()
    {
        return [
            [['mode_id'], 'integer'],
            [['name', 'content'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Mode::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'mode_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'mode_id' => $this->mode_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
