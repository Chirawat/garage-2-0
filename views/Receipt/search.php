<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = "ค้นหา"
?>
<h1><?=$this->title?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'book_number',
        'reciept_id',
        [
            'attribute' => 'date',
            'format' => ['date', 'php:d/m/Y'],
        ],
        [
            'attribute' => 'invoice.customer.fullname',
            'label' => 'ในนาม',
        ],
        [
            'format' => 'html',
            'attribute' => 'total',
            'value' => function($model){
                return '<div style="text-align: right;">' . number_format($model->total,2) . '</div>';
            }
        ],
        [
            'format' => 'html',
            'value' => function($model, $key){
                return '<a href="' . Url::to(['view-multiple-claim', 'rid' => $key]) . '" class="btn btn-default btn-sm">ดู</a>'; 
            }
        ],
    ],
]);