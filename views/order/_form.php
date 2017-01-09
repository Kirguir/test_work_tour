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

	<?= $form->field($model, 'status')
		->dropDownList(
			[Order::getActions()],
			['prompt'=>'Select a action', 'id'=>'action_dropdown']

        )->label(''); ?>

    <?= $form->field($model, 'sender_id')->textInput() ?>

    <?= $form->field($model, 'recipient_id')->textInput() ?>

    <?= $form->field($model, 'count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dtextInput() ?>

    <?= $form->field($model, 'process_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
