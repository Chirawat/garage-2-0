<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Viecle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="viecle-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'viecle_type')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'plate_no')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'viecle_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'brand')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'model')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'body_code')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'engin_code')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'viecle_year')->textInput() ?>

    <?= $form->field($model, 'body_type')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'cc')->textInput() ?>

    <?= $form->field($model, 'seat')->textInput() ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'owner')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
