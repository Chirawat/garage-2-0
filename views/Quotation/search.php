<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\grid\GridView;

$this->title = "ค้นหาใบเสนอราคา";
?>
<div class="row">
    <div class="container">
        <?php ActiveForm::begin(['options' => ['class' => 'form-inline']]); ?>
        <div class="form-group"> 
            <label>เลขทะเบียน</label>
            <?= Html::input('text', 'plate_no', '', ['class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </div>
        <?php ActiveForm::end(); ?>
        <br>
    </div>
</div>
<div class="row">
    <div class="container">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn'
                ],
                
                [
                    'attribute' => 'quotation_date',
                    'label' => 'วันที่ออก',
                    'format' => ['date', 'php: d/m/Y'], 
                ],
                [
                    'attribute' => 'quotation_id',
                    'label' => 'เลขที่',
                ],
                [
                    'attribute' => 'viecle.plate_no',
                ],
                [
                    'attribute' => 'customer.fullname',
                    'label' => 'ในนาม',
                ],
                
                [
                    'attribute' => 'claim.claim_no',
                    'label' => 'หมายเลขเคลม',
                ],
                [
                    'label' => '',
                    'value' => function($model){
                        return Html::a('ดู', ['quotation/view', 'qid' => $model->QID], ['class' => 'btn btn-default btn-sm']);
                    },  
                    'format' => 'html',
                ],
            ],
        ]);?>

    </div>
</div>

