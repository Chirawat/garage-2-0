<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\grid\GridView;

$this->title = "ค้นหาใบแจ้งหนี้";
?>
<h1><?=$this->title?></h1>
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
                    'attribute' => 'date',
                    'label' => 'วันที่ออกใบแจ้งหนี้',
                    'format' => ['date', 'php: d/m/Y'], 
                ],
                'book_number',
                [
                    'attribute' => 'invoice_id',
                    'label' => 'เลขที่ใบแจ้งหนี้',
                ],
                [
                    'label' => 'ประเภท',
                    'value' => function($model) {
                        return $model->type === 'General' ? "ลูกค้าทั่วไป" : "บริษัทประกัน";
                    },
                    'format' => 'html'
                ],
                'viecle.plate_no',
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
                        if ($model->type === null)
                            return Html::a('ดู', ['invoice/view', 'iid' => $model->IID], ['class' => 'btn btn-default btn-sm']);
                        else 
                            return Html::a('ดู', ['invoice/view', 'iid' => $model->IID, 'type' => 'General'], ['class' => 'btn btn-default btn-sm']);
                    },  
                    'format' => 'html',
                ],
            ],
        ]);?>

    </div>
</div>

