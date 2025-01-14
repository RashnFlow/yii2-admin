<?php

namespace wm\admin\models\ui\filter;

use yii\data\ActiveDataProvider;

class FilterItemSearch extends FilterItem
{
    public function search($params)
    {
        $query = parent::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>
                [
                    'pageSizeLimit' => [1, 10000],
                    'defaultPageSize' => 10000,
                ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        foreach ($this->rules()[0][0] as $value) {
            $query->andFilterCompare($value, $this->{$value});
        }
        return $dataProvider;
    }

    public function rules()
    {
        return [[array_keys($this->attributes), 'safe']];
    }
}
