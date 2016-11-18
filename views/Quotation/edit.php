<?php
use yii\jui\Dialog;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\View;

$this->title = "แก้ไขใบเสนอราคา";
?>
<div id="edit-maintenance-description" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">แก้ไขรายการซ่อม</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">รายการ</label>
                        <div class="form-group col-sm-10">
                            <input id="list" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ราคา</label>
                        <div class="form-group col-sm-5">
                            <input id="price" type="number" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
                <div class="modal-footer">
                <button id="desc-update" type="button" class="btn btn-primary" onclick="updateInvoiceDescription()">บันทึก</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="edit-part-description" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">แก้ไขรายการซ่อม</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">รายการ</label>
                        <div class="form-group col-sm-10">
                            <input id="list" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ราคา</label>
                        <div class="form-group col-sm-5">
                            <input id="price" type="number" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
                <div class="modal-footer">
                <button id="desc-update" type="button" class="btn btn-primary" onclick="updateInvoiceDescription()">บันทึก</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="container">
    <div class="col-sm-6">
        <label>เลขที่ <?=$quotationId?></label><br>
        <label>วันที่ <?=date('d-m-Y')?></label>
    </div>
    <div class="form-group pull-right">
        <a id="btn-edit-save" class="btn btn-primary btn-sm">
            <span class="glyphicon glyphicon-save-file"></span> บันทึกรายการ </a>
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
                            <input type="hidden" id="plate-no" value="<?=$viecle->VID?>">
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
                        <label class="control-label col-sm-3">ประเภทลูกค้า</label>
                        <div class="col-sm-9">
                            <label class="radio-inline">
                                <input type="radio" name="customer-type" id="customer-type" value="GENERAL" <?= $quotation->customer['type'] == "GENERAL" ? "checked":null?> disabled> ลูกค้าทั่วไป </label>
                            <label class="radio-inline">
                                <input type="radio" name="customer-type" id="customer-type" value="INSURANCE" <?= $quotation->customer['type'] == "INSURANCE_COMP"?"checked":null?> disabled> บริษัทประกัน </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">เลขที่เคลม</label>
                        <div class="col-sm-9">
                            <input id="claim-no" class="form-control input-sm" value="<?= $quotation->claim['claim_no'] ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="phone">บริษัทประกัน</label>
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
<div class="container">
    <table class="table table-bordered" id="myTable">
            <thead>
                <tr bgcolor="#000000">
                    <th width="5%" class="text-white" style="color:white;">ลำดับ</th>
                    <th width="25%" style="color:white;">รายการซ่อม</th>
                    <th width="15%" style="color:white;">ราคา</th>
                    <th></th>
                    <th width="25%" style="color:white;">รายการอะไหล่</th>
                    <th width="15%" style="color:white;">ราคา</th>
                    <th></th>
                </tr>
                <tr id="input-row">
                    <td></td>
                    <td>
                        <input class="form-control" type="text" id="maintenance-list" /> </td>
                    <td>
                        <input class="form-control" type="number" id="maintenance-price" /> </td>
                    <td>
                        <button class="btn btn-primary btn-xs" id="maintenance-add"><span class="glyphicon glyphicon-plus"></span></button>
                    </td>
                    <td>
                        <input class="form-control" type="text" id="part-list" /> </td>
                    <td>
                        <input class="form-control" type="number" id="part-price" /> </td>
                    <td>
                        <button class="btn btn-primary btn-xs" id="part-add"><span class="glyphicon glyphicon-plus"></span></button>
                    </td>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>รวมรายการซ่อม</td>
                    <td><div id="maintenance-total"></div></td>
                    <td></td>
                    <td>รวมรายการอะไหล่</td>
                    <td><div id="part-total"></div></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>รวมสุทธิ</td>
                    <td><div id="total"></div></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
</div>


<?php 
/* 20161019: push description model into javascript object/ this method is insecure. */
foreach($maintenanceDescriptionModel as $maintenanceDescription){
    $this->registerJS('maintenance.push({
        list: ' . json_encode($maintenanceDescription->description, JSON_HEX_TAG) . ',
        price: ' . json_encode($maintenanceDescription->price, JSON_HEX_TAG) . '});', VIEW::POS_END);
}
foreach($partDescriptionModel as $partDescription){
    $this->registerJS('part.push({
        list: ' . json_encode($partDescription->description, JSON_HEX_TAG) . ',
        price: ' . json_encode($partDescription->price, JSON_HEX_TAG)  . '});', VIEW::POS_END);
}

$this->registerJS('renderTableBody();calTotal();updateTableIndex();', VIEW::POS_READY);
?>
