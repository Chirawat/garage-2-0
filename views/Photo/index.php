<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "เพิ่มรูปภาพ";
?>
    <div id="add-claim-no" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?php $form = ActiveForm::begin(['action' => ['claim/create']]); ?>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">เพิ่มหมายเลขเคลม</h4> </div>
                    <div class="modal-body">
                        <?= $form->field($claim, 'claim_no')->label('หมายเลขเคลม') ?>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">เพิ่ม</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="row">
        <div class="container">
            <div class="pull-right">
                <a href="#" class="btn btn-success btn-sm">พิมพ์หน้าปัจจุบัน</a>
                <a href="#" class="btn btn-success btn-sm">พิมพ์ทั้งหมด</a> </div>
        </div>
    </div>
    <br>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php $form_upload = ActiveForm::begin(['options' => [
                'class' => 'form-horizontal',
                'encrype' => 'multipart/form-data',]]); ?>
                <div class="form-group">
                    <label class="control-label col-sm-2">เลขที่เคลม</label>
                    <div class="col-sm-3">
                        <?= Html::activeDropDownList($photo, 'CLID', ArrayHelper::map($claim_t, 'CLID', 'claim_no'), [
                            'id' => 'claim-no',
                            'class' => 'form-control',
                            'prompt' => 'เลือกเลขที่เคลม',
                            'options' => [$selectedKey => ['Selected' => true],]
                        ]) ?>
                    </div> <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-claim-no">เพิ่ม</a> </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">ช่วงเวลา</label>
                    <div class="col-sm-3">
                        <?= Html::activeDropDownList($photo, 'type', [
                            'BEFORE' => 'ภาพก่อนซ่อม',
                            'DURING' => 'ภาพขณะซ่อม',
                            'COMPARE' => 'เทียบอะไหล่',
                            'AFTER' => 'ซ่อมเสร็จ',
                            'OTHER' => 'อื่น ๆ'
                        ],[
                            'id' => 'type',
                            'class' => 'form-control input-sm',
                            'options' => [$selectedType => ['Selected' => true]],
                        ]) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile" class="control-label col-sm-2">ไฟล์</label>
                    <div class="col-sm-3">
                        <div class="container">
                            <?= $form_upload->field($photo, 'imageFile')->fileInput()->label(false)->hint('ไฟล์ jpg ขนาดไม่เกิน 10 MB') ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-3">
                        <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="row">
        <div class="container">
            <div id="result"><?=$details?></div>
        </div>
    </div>
