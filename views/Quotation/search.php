<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

if( isset($quotations) ) 
    var_dump( $quotations );
?>


<div class="col-sm-4">
    <?php ActiveForm::begin() ?>
    
    <div class="form-group">
        <label>เลขทะเบียน</label>
        <?= Html::input('text', 'plate_no', '', ['class' => 'form-control']) ?>
    </div>
    
    <div class="form-group">
        <label>เลขที่ใบเสนอราคา</label>
        <?= Html::input('text', 'quotation_id', null, ['class' => 'form-control']) ?>
    </div>
    
    <button type="submit" class="btn btn-primary">ค้นหา</button>
    <?php ActiveForm::end() ?>
</div>