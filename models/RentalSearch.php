<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rental;
use PHPUnit\Framework\MockObject\Builder\Identity;

/**
 * RentalSearch represents the model behind the search form of `app\models\Rental`.
 */
class RentalSearch extends Rental
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'book_id'], 'integer'],
            [['rent_start', 'rent_end', 'returned_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Rental::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[    'pageSize' => 10 ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'book_id' => $this->book_id,
            'rent_start' => $this->rent_start,
            'rent_end' => $this->rent_end,
            'returned_at' => $this->returned_at,
        ]);
        $query->andWhere(['user_id'=> \Yii::$app->user->Identity->id]);

        $query->orderBy("rent_start  desc");
        return $dataProvider;
    }
}
