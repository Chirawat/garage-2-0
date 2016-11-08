<?php
use yii\jui\Dialog;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\View;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$this->title = "ใบเสนอราคา";
?>
<div class="container">
        <div class="col-sm-6">
            <label>เล่มที่ ...... เลขที่ ..............</label><br>
            <label>วันที่ ..............</label>
        </div>
        <div class="form-group pull-right">
            <a href="<?=Url::to(['quotation/edit', 'qid' => $quotation->QID])?>" id="btn-edit" class="btn btn-primary btn-sm">
                <span class="glyphicon glyphicon-save-file"></span> แก้ไขรายการ </a>
            <a id="btn-print" target="_blank" class="btn btn-success btn-sm">
                <span class="glyphicon glyphicon-print"></span> พิมพ์ใบเสนอราคา </a>
        </div>
</div>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-sm-6">
                <form name="test" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">ทะเบียน</label>
                        <div class="col-sm-4">
                            <input disabled value="<?= $viecle->plate_no ?>" class="form-control input-sm">
                        </div>
                        <label class="col-sm-2 control-label">ชื่อรถ</label>
                        <div class="col-sm-3">
                            <input type="text" id="viecle-name" value="<?= $viecle->viecleName['name'] ?>" class="form-control input-sm" readonly> </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">รุ่น</label>
                        <div class="col-sm-4">
                            <input type="text" id="viecle-model" value="<?= $viecle->viecleModel['model'] ?>" class="form-control input-sm" readonly> </div>
                        <label class="col-sm-2 control-label">ปี</label>
                        <div class="col-sm-3">
                            <input type="text" id="year" value="<?= $viecle->viecle_year ?>" class="form-control input-sm" readonly> </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">เลขตัวถัง</label>
                        <div class="col-sm-9">
                            <input type="text" id="body-code" value="<?= $viecle->body_code ?>" class="form-control input-sm" readonly> </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">เลขเครื่อง</label>
                        <div class="col-sm-9">
                            <input type="text" id="engine-code" value="<?= $viecle->engin_code ?>" class="form-control input-sm" readonly> </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="customer">ชื่อ</label>
                        <div class="col-sm-9">
                            <input type="text" id="fullname" value="<?=$viecle->owner0['fullname'] ?>" class="form-control input-sm" readonly> </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="adress">ที่อยู่</label>
                        <div class="col-sm-9">
                            <textarea id="address" class="form-control input-sm" rows="2" readonly><?= $viecle->owner0['address'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="customer">โทรศัพท์</label>
                        <div class="col-sm-9">
                            <input type="text" id="phone" value="<?= $viecle->owner0['phone'] ?>" class="form-control input-sm" readonly> </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-3">เลขที่เคลม</label>
                        <div class="col-sm-9">
                            <input id="claim-no" class="form-control input-sm" value="<?= $quotation->claim_no ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">ประเภทลูกค้า</label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <input type="radio" name="customer-type" id="customer-type" value="GENERAL" <?= $quotation->customer['type'] == "GENERAL" ? "checked":null?> disabled> ลูกค้าทั่วไป </label>
                            <label class="radio-inline">
                                <input type="radio" name="customer-type" id="customer-type" value="INSURANCE" <?= $quotation->customer['type'] == "INSURANCE_COMP"?"checked":null?> disabled> บริษัทประกัน </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="phone">นามลูกค้า</label>
                        <div class="col-sm-9">
                            <input type="text" disabled class="form-control input-sm" value="<?=$quotation->customer['fullname']?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">ความเสียหาย</label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <input type="radio" name="damage-level" id="damage-level" value="1" <?= $quotation->damage_level=="1"?"checked":null?> disabled> น้อย </label>
                            <label class="radio-inline">
                                <input type="radio" name="damage-level" id="damage-level" value="2" <?= $quotation->damage_level=="2"?"checked":null?> disabled> ปานกลาง </label>
                            <label class="radio-inline">
                                <input type="radio" name="damage-level" id="damage-level" value="3" <?= $quotation->damage_level=="3"?"checked":null?> disabled> มาก </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="phone">ตำแหน่งการชน</label>
                        <div class="col-sm-9">
                            <input type="text" disabled class="form-control input-sm" value="<?= $quotation->damagePosition['position']?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php Pjax::begin(); ?>
<div class="container">
    <div class="form-group">
        <?php $form = ActiveForm::begin(['options' => [
            'data-pjax' => true,
            'class' => 'form-inline']]) ?>
            <label class="form-label">ประวัติการแก้ไข</label>
            <select id="history-date" name="dateIndex" class="form-control input-sm">
                <div class="col-sm-3">
                    <?php $i= 0; foreach($dateLists as $date): ?>
                        <option value="<?= $i ?>" <?= $i==$dateIndex?"selected":null?> ><?= $date->date ?> <?= $i==0 ? "(ล่าสุด)":null ?></option>
                    <?php $i++; endforeach; ?>
                </div>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">ค้น</button>
        <?php ActiveForm::end() ?>
    </div>
    <table class="table table-bordered" id="table-edit">
        <thead>
            <tr bgcolor="#000000">
                <th class="text-white" style="color:white;">ลำดับ</th>
                <th class="col-sm-4" style="color:white;">รายการซ่อม</th>
                <th class="col-sm-2" style="color:white;">ราคา</th>
                <th class="col-sm-4" style="color:white;">รายการอะไหล่</th>
                <th class="col-sm-2" style="color:white;">ราคา</th>
            </tr>
        </thead>
        <tbody>
            <?php for($i = 0; $i < $numRow; $i++):?>
                <tr>
                    <td style="text-align: center;"><?= ($i + 1) ?></td>
                    <td><?= isset($maintenanceDescriptionModel[$i]) ? $maintenanceDescriptionModel[$i]->description:null ?></td>
                    <td class="text-right"><?= isset($maintenanceDescriptionModel[$i]) ? number_format( $maintenanceDescriptionModel[$i]->price, 2 ):null ?></td>
                    <td><?= isset($partDescriptionModel[$i]) ? $partDescriptionModel[$i]->description:null ?></td>
                    <td class="text-right"><?= isset($partDescriptionModel[$i]) ? number_format( $partDescriptionModel[$i]->price, 2 ):null ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td>รวมรายการซ่อม</td>               
                <td><?= number_format( $sumMaintenance, 2 ) ?></td>
                <td>รวมรายการอะไหล่</td>
                <td><?= number_format( $sumPart, 2 ) ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>รวมสุทธิ</td>
                <td><?= number_format( $sumMaintenance + $sumPart, 2 ) ?></td>
            </tr>
        </tfoot>
    </table>
    
</div>
<?php Pjax::end(); ?>
