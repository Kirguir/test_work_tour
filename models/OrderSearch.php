<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch extends Order
{
	public $sender_name;
	public $recipient_name;


	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
			[['sender_name', 'recipient_name'], 'string', 'max' => 15],
            [['count'], 'number'],
            [['process_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		$query->with('sender', 'recipient');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sender.nickname' => $this->sender_name,
            'recipient.nickname' => $this->recipient_name,
            'count' => $this->count,
            'status' => $this->status,
            'process_time' => $this->process_time,
        ]);

        return $dataProvider;
    }
}
