<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Invoice;
use app\models\Receipt;
use yii\grid\GridView;

$this->title = "รายงานค้างจ่าย";

?>

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
    <a target="_blank" href="<?=Url::to(['receipt/dept-report', 'dataProvider' => $dataProvider])?>" class="btn btn-success">พิมพ์</a>

<?php ActiveForm::end() ?>
<br>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn'
        ],
        [
            'attribute' => 'date',
            'label' => 'วันที่ออกใบแจ้งหนี้',
            'format' => [
                'date', 'php: d/m/Y',
            ],
        ],
        [
            'attribute' => 'invoice_id',
            'label' => 'เลขที่ใบแจ้งหนี้'
        ],
        [
            'attribute' => 'customer.fullname',
            'label' => 'ในนาม',
        ],
        [
            'label' => 'สถานะ',
            'format' => 'html',
            'value' => function($model){
                if( $model->reciept['RID'] != "")
                    return '<span class="label label-success">จ่ายแล้ว</span>';
                else
                    return '<span class="label label-danger">ค้างจ่าย</span>';
            },
        ],
        [
            'attribute' => 'reciept.reciept_id',
            'label' => 'เลขที่ใบเสร็จ',
        ],
        [
            'attribute' => 'reciept.date',
            'label' => 'วันที่ออกใบเสร็จ',
            'format' => ['date', 'php: d/m/Y'],
        ],
    ],
])?>
