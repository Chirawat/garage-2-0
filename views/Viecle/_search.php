<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ViecleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="viecle-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'VID') ?>

    <?= $form->field($model, 'viecle_type') ?>

    <?= $form->field($model, 'plate_no') ?>

    <?= $form->field($model, 'viecle_name') ?>

    <?= $form->field($model, 'brand') ?>

    <?php // echo $form->field($model, 'model') ?>

    <?php // echo $form->field($model, 'body_code') ?>

    <?php // echo $form->field($model, 'engin_code') ?>

    <?php // echo $form->field($model, 'viecle_year') ?>

    <?php // echo $form->field($model, 'body_type') ?>

    <?php // echo $form->field($model, 'cc') ?>

    <?php // echo $form->field($model, 'seat') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'owner') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
