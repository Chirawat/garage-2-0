<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = "บริษัทประกัน";
?>
<div id="create-viecle-name" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php $form = ActiveForm::begin(); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">เพิ่มชื่อรถยนต์</h4>
            </div>
            <div class="modal-body">
                <?= $form->field($viecleName_t, 'name') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">เพิ่ม</button>
            </div>
        </div><!-- /.modal-content -->
        <?php ActiveForm::end(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="create-viecle-model" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php $form_t = ActiveForm::begin(); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">เพิ่มรุ่นรถยนต์</h4>
            </div>
            <div class="modal-body">
                <?= $form_t->field($viecleModel, 'viecle_name')->dropDownList(ArrayHelper::map($viecleName, 'id', 'name'))?>
                <?= $form_t->field($viecleModel, 'model')?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">เพิ่ม</button>
            </div>
        </div><!-- /.modal-content -->
        <?php ActiveForm::end(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<h1>ชื่อและรุ่นรถยนต์</h1>
<form class="form-inline">
    <label>ชื่อรถยนต์</label>
    <?= Html::dropDownList('filter-viecle-name', null, ArrayHelper::map($viecleName, 'id', 'name'),  ['id' => 'filter-viecle-name', 'class' => 'form-control']) ?>
    <a href="#create-viecle-name" class="btn btn-success" data-toggle="modal">เพิ่ม</a>
</form>
<br>
<div class="row">
    <div class="container">
        <div class="form-group">
            <a href="#create-viecle-model" class="btn btn-success" data-toggle="modal">เพิ่มรุ่นรถยนต์</a>
        </div>
        <div id="viecle-model-result">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'viecleName.name',
                        'label' => 'ชื่อรถยนต์',
                    ],
                    [
                        'attribute' => 'model',
                        'label' => 'ชื่อรุ่น'
                    ],
                ],
            ]);?>
        </div>
    </div>
</div>