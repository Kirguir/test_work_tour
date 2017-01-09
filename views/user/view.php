<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nickname',
            'authKey',
            'accessToken',
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

    <?= GridView::widget([
        'dataProvider' => $dataProviderSend,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
			[
				'attribute' => 'sender_name',
				'value' => 'sender.nickname'
			],
			[
				'attribute' => 'recipient_name',
				'value' => 'recipient.nickname'
			],
            'count',
            'status',
            // 'process_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderReceive,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
				'attribute' => 'sender_name',
				'value' => 'sender.nickname'
			],
			[
				'attribute' => 'recipient_name',
				'value' => 'recipient.nickname'
			],
            'count',
            'status',
            // 'process_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
