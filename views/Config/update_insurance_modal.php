<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'action' => Url::to(['config/update-insurance', 'CID' => $customer->CID]),
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-8',
        ]
    ]]) ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">แก้ไขบริษัทประกัน</h4>
</div>
<div class="modal-body">
    <?= $form->field($customer, 'fullname')->textInput()?>

    <?= $form->field($customer, 'address')->textArea() ?>

    <?= $form->field($customer, 'phone') ?>

    <?= $form->field($customer, 'fax') ?>

    <?= $form->field($customer, 'taxpayer_id') ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
    <button type="submit" class="btn btn-primary">แก้ไข</button>
</div>
<?php ActiveForm::end(); ?>