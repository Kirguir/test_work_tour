<?php

use yii\helpers\Html;
use app\models\Order;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'action')
		->dropDownList(
			Order::getActions(),
			['id'=>'action_dropdown']

		)->label(''); ?>

    <?= $form->field($model, 'recipient_name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'count')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
