<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "บริษัทประกัน";
?>

<div id="new-insurance" class="modal fade" tabindex="-1" role="dialog">
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
                    <h4 class="modal-title">เพิ่มบริษัทประกัน</h4>
                </div>
                <div class="modal-body">

                    <?= $form->field($customer, 'fullname')->textInput()?>

                    <?= $form->field($customer, 'address')->textArea() ?>

                    <?= $form->field($customer, 'phone') ?>

                    <?= $form->field($customer, 'fax') ?>

                    <?= $form->field($customer, 'taxpayer_id') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">เพิ่ม</button>
                </div>
            </div><!-- /.modal-content -->
        <?php ActiveForm::end(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<h1><?=$this->title?></h1>

<a href="#new-insurance" class="btn btn-success" data-toggle="modal">เพิ่ม</a>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'fullname',
            'label' => 'บริษัทประกัน',
        ],
        [
            'attribute' => 'address',
            'label' => 'ที่อยู่',
        ],
        [
            'attribute' => 'phone',
            'label' => 'โทรศัพท์',
        ],
        [
            'attribute' => 'fax',
            'label' => 'แฟกซ์',
        ],
        [
            'attribute' => 'taxpayer_id',
            'label' => 'หมายเลขประจำตัวผู้เสียภาษี',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'visibleButtons' => [
                'view' => false,
                'delete' => false,
            ],
        ],
    ],
])?>