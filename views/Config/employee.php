<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = "พนักงาน";
?>
<div id="create-employee" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php $form = ActiveForm::begin(['layout' => 'horizontal',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'wrapper' => 'col-sm-8',
                ]
            ]]) ?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">เพิ่มพนักงาน</h4>
                </div>
                <div class="modal-body">
                    <?= $form->field($employee, 'fullname') ?>
                    <?= $form->field($employee, 'Position') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">เพิ่ม</button>
                </div>
            </div><!-- /.modal-content -->
        <?php ActiveForm::end(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal-employee-update" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php $form = ActiveForm::begin(['layout' => 'horizontal',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'wrapper' => 'col-sm-8',
                ]
            ]]) ?>
            <div class="modal-content">
                
            </div><!-- /.modal-content -->
        <?php ActiveForm::end(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<h1><?=$this->title?></h1>
<div class="form-group">
    <a href="#create-employee" class="btn btn-success" data-toggle="modal"><span class="glyphicon glyphicon-plus"></span> เพิ่มพนักงาน</a>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'EID',
            'label' => 'รหัสพนักงาน',
        ],
        [
            'attribute' => 'fullname',
            'label' => 'ชื่อพนักงาน',
        ],
        [
            'attribute' => 'Position',
            'label' => 'ตำแหน่ง',
        ],
        [
            'format' => 'html',
            'value' => function($model){
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', ['class' => 'btn btn-default btn-sm modal-employee-update']);
            },
        ],
    ],
]);