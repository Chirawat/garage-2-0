<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
?>
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
        'owner0.fullname'
    ],
]);