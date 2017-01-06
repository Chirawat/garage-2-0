<?php
use yii\jui\Dialog;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\View;

$this->title = "ใบเสนอราคา / rev2";
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
        <a href="<?=Url::to(['quotation/search'])?>" class="btn btn-primary btn-sm">
            <span class="glyphicon glyphicon-save-file"></span> ค้นหาใบเสนอราคา </a>
<!--
        <a href="<?=Url::to(['viecle/index'])?>" class="btn btn-primary btn-sm">
            <span class="glyphicon glyphicon-save-file"></span> จัดการข้อมูลรถ </a>
-->
        <a id="btn-save" class="btn btn-primary btn-sm">
            <span class="glyphicon glyphicon-save-file"></span> บันทึก </a>
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
                                <?=Html::DropDownList('plate_no', 0, ArrayHelper::map($viecleList, 'VID', 'plate_no'),[
                                    'prompt' => 'เลือกทะเบียนรถ',
                                    'id' => 'plate-no',
                                    'class' => 'form-control',])?>
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
                                <textarea id="viecle-address" class="form-control input-sm" rows="2" readonly><?= $viecle->owner0['address'] ?></textarea>
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
                                <input id="claim-no" class="form-control input-sm" value="<?= $quotation->claim['claim_no'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">ประเภทลูกค้า</label>
                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" name="customer-type" id="customer-type" value="GENERAL"> ลูกค้าทั่วไป </label>
                                <label class="radio-inline">
                                    <input type="radio" name="customer-type" id="customer-type" value="INSURANCE_COMP"> บริษัทประกัน </label>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-sm-3">นามลูกค้า</label>
                            <div id="customer-list" class="col-sm-9">
                                <select class="form-control input-sm"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">ความเสียหาย</label>
                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" name="damage-level" id="damage-level" value="1" checked> น้อย </label>
                                <label class="radio-inline">
                                    <input type="radio" name="damage-level" id="damage-level" value="2"> ปานกลาง </label>
                                <label class="radio-inline">
                                    <input type="radio" name="damage-level" id="damage-level" value="3"> มาก </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="phone">ตำแหน่งการชน</label>
                            <div class="col-sm-9">
                                <select id="damage-position" class="form-control input-sm"z>
                                    <?php foreach($damagePostions as $damagePosition): ?>
                                        <option value="<?= $damagePosition->id ?>">
                                            <?= $damagePosition->position ?>
                                        </option>
                                        <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-7 col-sm-offset-3">
                                <a id="auto-generate" class="btn btn-primary btn-sm">สร้างรายการอัตโนมัติ</a>
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
                    <td>
                        <div id="maintenance-total"></div>
                        <div id="maintenance-total-editing" style="display: none;">
                            <input type="text" class="form-control text-right">
                        </div>
                    </td>
                    <td></td>
                    <td>รวมรายการอะไหล่</td>
                    <td>
                        <div id="part-total"></div>
                        <div id="part-total-editing" style="display: none;">
                            <input type="text" class="form-control text-right">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>รวมสุทธิ</td>
                    <td>
                        <div id="total"></div>
                        <div id="total-editing" style="display: none;">
                            <input type="text" class="form-control text-right">
                        </div>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        * ช่วงรวม Double Click เพื่อทำการแก้ไข/กด enter เพื่อยืนยันการแก้ไข
    </div>
<?php $this->registerJS('$("#plate-no").select2();', View::POS_READY);?>
