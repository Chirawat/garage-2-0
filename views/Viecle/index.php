<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$this->title = "ข้อมูลรถ";
?>

<div id="update-viecle" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<h1><?=$this->title?></h1>
<div class="form-group">
    <a href="<?=Url::to(['viecle/create'])?>" class="btn btn-success" data-toggle="modal"><span class="glyphicon glyphicon-plus"></span> เพิ่ม</a>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'plate_no',
        'viecleName.name',
        'viecleModel.model',
        'body_code',
        'owner0.fullname',
        [
            'format' => 'raw',
            'value' => function($model){
                return Html::a('ดู/แก้ไขข้อมูล', ['viecle/update', 'VID' => $model->VID], ['class' => 'btn btn-default btn-sm']);
            },
        ]
    ],
]);?>
