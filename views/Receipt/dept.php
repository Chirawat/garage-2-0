<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Invoice;
use app\models\Receipt;
use yii\grid\GridView;

$this->title = "รายงานค้างจ่าย";

?>

<h1><?=$this->title?></h1>

<!-- 
<?php ActiveForm::begin([
'options' => [
    'class' => 'form-inline' ]]) ?>
    <div class="form-group">
        <label>จาก</label>
        <?= Html::dropDownList('start-date', null, $invoiceDates, ['class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <label>ถึง</label>
        <?= Html::dropDownList('end-date', null, $invoiceDates, ['class' => 'form-control']) ?>
    </div>
    <button type="submit" class="btn btn-primary">ตกลง</button>
<?php ActiveForm::end() ?>
<br>
-->

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn'
        ],
        'claim_no',
        [
            'attribute' => 'invoice.date',
            'label' => 'วันที่ออกใบแจ้งหนี้',
            'format' => [
                'date', 'php: d/m/Y',
            ],
        ],
        [
            'attribute' => 'invoice.book_number', 
            'label' => 'เล่มที่'
        ],
        [
            'attribute' => 'invoice.invoice_id',
            'label' => 'เลขที่ใบแจ้งหนี้'
        ],
        [
            'attribute' => 'invoice.customer.fullname',
            'label' => 'ในนาม',
        ],
        [
            'label' => 'สถานะ',
            'format' => 'html',
            'value' => function($model){
                if( $model->paymentStatus != null)
                    return '<span class="label label-success">จ่ายแล้ว</span>';
                else
                    return '<span class="label label-danger">ค้างจ่าย</span>';
            },
        ],
        [
            'attribute' => 'paymentStatus.receipt.book_number', 
            'label' => 'เล่มที่',
        ],
        [
            'attribute' => 'paymentStatus.receipt.reciept_id',
            'label' => 'เลขที่ใบเสร็จ',
        ],
        [
            'attribute' => 'paymentStatus.receipt.date',
            'label' => 'วันที่ออกใบเสร็จ',
            'format' => ['date', 'php: d/m/Y'],
        ],
    ],
])?>
