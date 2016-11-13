<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'action' => Url::to(['config/update-employee', 'EID' => $employee->EID]),
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-8',
        ]
    ]]) ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">แก้ไขข้อมูลพนักงาน</h4>
</div>
<div class="modal-body">
    <?= $form->field($employee, 'fullname') ?>
    <?= $form->field($employee, 'Position') ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
    <button type="submit" class="btn btn-primary">แก้ไข</button>
</div>
<?php ActiveForm::end(); ?>