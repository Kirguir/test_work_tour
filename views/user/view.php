<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Order;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->nickname;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			[
				'label'  => 'Balance',
				'value'  => $model->balance,
			],
        ],
    ]) ?>

</div>

<div class="order-index">
    <h1>Orders</h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Order', ['order/create'], ['class' => 'btn btn-success']) ?>
    </p>

	<h2>Sent payments</h2>

    <?= GridView::widget([
        'dataProvider' => $dataProviderSend,
        'filterModel' => $searchModel,
		'rowOptions'=>function ($model, $key, $index, $grid){
			if ( $model->status == Order::STATUS_ACCEPTED ) {
				$class = 'success';
			} elseif ($model->status == Order::STATUS_DECLINED) {
				$class = 'danger';
			} else {
				$class = 'warning';
			}
			
			return [
				'key'=>$key,
				'index'=>$index,
				'class'=>$class
			];
		},
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
			'sender_name',
			'recipient_name',
            'count',
            'status',
            'process_time',

            [
				'class' => 'yii\grid\ActionColumn',
				'controller' => 'order',
				'template' => '{view}'
			],
        ],
    ]); ?>

	<h2>Payments received</h2>

    <?= GridView::widget([
        'dataProvider' => $dataProviderReceive,
        'filterModel' => $searchModel,
		'rowOptions'=>function ($model, $key, $index, $grid){
			if ( $model->status == Order::STATUS_ACCEPTED ) {
				$class = 'success';
			} elseif ($model->status == Order::STATUS_DECLINED) {
				$class = 'danger';
			} else {
				$class = 'warning';
			}

			return [
				'key'=>$key,
				'index'=>$index,
				'class'=>$class
			];
		},
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sender_name',
			'recipient_name',
            'count',
            'status',
            'process_time',

            [
				'class' => 'yii\grid\ActionColumn',
				'controller' => 'order',
				'template' => '{view}'
			],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
