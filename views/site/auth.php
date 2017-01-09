<?php
/* @var $this yii\web\View */
/* @var $model app\models\AuthForm */
/* @var $form ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-auth">
	<h1><?= Html::encode($this->title) ?></h1>
    <p>Пожалуйста, введите логин:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'auth-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'nickname') ?>
    
        <div class="form-group">
			<div class="col-lg-offset-1 col-lg-11">
				<?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
			</div>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-auth -->
