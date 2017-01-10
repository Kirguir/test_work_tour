<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::$app->user->identity->nickname, 'url' => ['user/view', 'id' => Yii::$app->user->identity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

	<p>
		<?php if(!$model->status && $model->sender_name === Yii::$app->user->identity->nickname) { ?>
			<?= Html::a('Accept', ['accept', 'id' => $model->id], [
				'class' => 'btn btn-primary',
				'data' => [
					'method' => 'post',
				],
			]) ?>
		<?php } ?>
		<?php if(!$model->status) { ?>
			<?= Html::a('Decline', ['decline', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => 'Are you sure you want to decline this order?',
					'method' => 'post',
				],
			]) ?>
		</p>
	<?php } ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sender_name',
            'recipient_name',
            'count',
            'status',
            'process_time',
        ],
    ]) ?>

</div>
