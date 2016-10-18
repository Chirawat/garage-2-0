<?php
use yii\jui\Dialog;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\jui\AutoComplete;
use yii\web\View;
?>
    <div class="modal fade" id="customer" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modal title</h4> </div>
                <div class="modal-body">
                    <select multiple class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="container">
        <form class="form-inline">
            <div class="form-group">
                <label class="form-label">เลขที่</label>
                <input type="text" class="form-control input-sm" readonly value="1/2559"> </div>
            <div class="form-group">
                <label class="form-label">วันที่</label>
                <input type="text" class="form-control input-sm" readonly value="1/10/2559"> </div>
            <div class="form-group pull-right">
                <a href="<?=Url::to(['quotation/search'])?>" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-save-file"></span> ค้นหาใบเสนอราคา </a>
                <a href="<?=Url::to(['viecle/index'])?>" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-save-file"></span> จัดการข้อมูลรถ </a> |
                <a id="btn-save" class="btn btn-primary btn-sm"> <span class="glyphicon glyphicon-save-file"></span> บันทึก </a>
                <a id="btn-print" target="_blank" class="btn btn-success btn-sm"> <span class="glyphicon glyphicon-print"></span> พิมพ์ใบเสนอราคา </a>
            </div>
        </form>
    </div>
    <br>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-6">
                    <form name="test" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ทะเบียน</label>
                            <div class="col-sm-4">
                                <?=Html::DropDownList('plate_no', 0, ArrayHelper::map($viecleList, 'VID', 'plate_no'),[
                                    'id' => 'plate-no',
                                    'class' => 'form-control',
                                    'onchange' => '$.post("' . Url::to(['viecle/viecle-detail']) . '", {VID: $(this).val()}, function(data){ updateViecleDetail( data ); });']) ?>
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
                                <textarea id="address" class="form-control input-sm" rows="2" readonly>
                                    <?= $viecle->owner0['address'] ?>
                                </textarea>
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
                            <div class="col-sm-7">
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> ลูกค้าทั่วไป </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> บริษัทประกัน </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">เลขที่เคลม</label>
                            <div class="col-sm-7">
                                <input class="form-control input-sm"> </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="phone">บริษัทประกัน</label>
                            <div class="col-sm-7">
                                <select class="form-control input-sm">
                                    <?php foreach($insuranceCompanies as $insuranceCompany): ?>
                                        <option value="<?= $insuranceCompany->CID ?>">
                                            <?= $insuranceCompany->fullname ?>
                                        </option>
                                        <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-save-file"></span> จัดการ</button>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">ความเสียหาย</label>
                            <div class="col-sm-7">
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> น้อย </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> ปานกลาง </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> มาก </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="phone">ตำแหน่งการชน</label>
                            <div class="col-sm-7">
                                <select class="form-control input-sm">
                                    <?php foreach($damagePostions as $damagePosition): ?>
                                        <option value="<?= $damagePosition->id ?>">
                                            <?= $damagePosition->position ?>
                                        </option>
                                        <?php endforeach; ?>
                                </select>
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
                    <th class="text-white" style="color:white;">ลำดับ</th>
                    <th class="col-sm-4" style="color:white;">รายการซ่อม</th>
                    <th class="col-sm-2" style="color:white;">ราคา</th>
                    <th class="col-sm-4" style="color:white;">รายการอะไหล่</th>
                    <th class="col-sm-2" style="color:white;">ราคา</th>
                    <th></th>
                </tr>
                <tr id="input-row">
                    <td></td>
                    <td>
                        <input class="form-control" type="text" id="maintenance-list" /> </td>
                    <td>
                        <input class="form-control" type="number" id="maintenance-price" /> </td>
                    <td>
                        <input class="form-control" type="text" id="part-list" /> </td>
                    <td>
                        <input class="form-control" type="number" id="part-price" /> </td>
                    <td>
                        <button class="btn btn-primary btn-xs" id="add-button"><span class="glyphicon glyphicon-plus"></span></button>
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
                    <td>รวมรายการอะไหล่</td>
                    <td><div id="part-total"></div></td>
                    <td></td>
                </tr>
                <tr>
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
$this->registerJS('$("#plate-no").select2();', View::POS_READY);
$str = 'function updateViecleDetail(data){ 
    $("#viecle-name").val( data.viecle_name );
    $("#viecle-model").val( data.viecle_model );
    $("#year").val( data.year );
    $("#engine-code").val( data.engine_code );
    $("#body-code").val( data.body_code );
    
    $("#fullname").val( data.fullname );
    $("#address").val( data.address );
    $("#phone").val( data.phone );
}';
$this->registerJS( $str, View::POS_BEGIN);

if( $viecle->plate_no != "" )
    $this->registerJS( '$("select#plate-no").append("<option disabled selected value>'.$viecle->plate_no.'</option>")', View::POS_READY );
else
    $this->registerJS( '$("select#plate-no").append("<option disabled selected value>เลือกทะเบียนรถ</option>")', View::POS_READY );


?>