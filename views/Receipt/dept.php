<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Invoice;
use app\models\Receipt;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$this->title = "รายงานค้างจ่าย";


$dataProvider = new ActiveDataProvider([
    'query' => Invoice::find()->with('reciept'),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
?>

<?php ActiveForm::begin([
'options' => [
    'class' => 'form-inline' ]]) ?>
    <div class="form-group">
        <label>จาก</label>
        <?= Html::dropDownList('start-date', null, $receiptDate, ['class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <label>ถึง</label>
        <?= Html::dropDownList('end-date', null, $receiptDate, ['class' => 'form-control']) ?>
    </div>
    <button type="submit" class="btn btn-primary">ตกลง</button>
    <?php if($month != []): ?>
        <a target="_blank" href="<?=Url::to(['receipt/summary-report', 'startDate' => $startDate , 'endDate' => $endDate ])?>" class="btn btn-success">พิมพ์</a>
    <?php else: ?>
        <a class="btn btn-success disabled">พิมพ์</a>
    <?php endif; ?>
<?php ActiveForm::end(); ?>
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
            'format' => ['date', 'php: d-m-Y']
        ],
        [
            'attribute' => 'invoice_id',
            'label' => 'เลขที่ใบแจ้งหนี้'
        ],
        [
            'label' => 'สถานะ',
            'value' => function($model, $key, $index, $column){
                if( !empty($key) )
                    return 'จ่ายแล้ว';
                else
                    return '-';
            },
        ],
        [
            'attribute' => 'reciept.reciept_id',
            'label' => 'เลขที่ใบเสร็จ'
        ],
        [
            'attribute' => 'reciept.date',
            'label' => 'วันที่ออกใบเสร็จ',
            'format' => ['date', 'php: d-m-Y'],
        ],
    ],
]);
