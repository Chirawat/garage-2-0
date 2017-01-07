<?php
// http://www.bsourcecode.com/2013/04/yii-ajax-request-and-json-response-array/
//use Yii;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\jui\AutoComplete;
use yii\jui\JuiAsset;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\web\View;
$this->title = 'ใบเสนอราคา/ใบเสร็จ';

$url = Url::to(['customer-list']);?>

<div id="customerSearch" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">รายชื่อลูกค้า</h4> </div>
                <div class="modal-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label>ประเภทลูกค้า</label>
                            <select name="customer-type" id="customer-type" class="form-control">
                                <option value="1">ลูกค้าทั่วไป</option>
                                <option value="2">บริษัทประกัน</option>
                            </select>
                            <label>ชื่อลูกค้า</label>
                            <input id="fullname" type="text" class="form-control">
                            <a id="btn-search" class="btn btn-primary">ค้น</a>
                        </div>
                    </form>
                    <div id="result"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
<div id="edit-invoice-description" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">แก้ไขรายการ</h4>
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
<div id="del-confirm" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">ยืนยันการลบ</h4>
                </div>
                <div class="modal-body">
                    <p>คุณต้องการจะลบรายการนี้หรือไม่</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary">ตกลง</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<div id="customerCreate" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">เพิ่มชื่อลูกค้า</h4>
            </div>
            <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
            <div class="modal-body">
                <?= $form->field($customer_t, 'type')->radioList(['GENERAL' => 'ลูกค้าทั่วไป', 'INSURANCE_COMP' => 'บริษัทประกัน'])->label('ประเภท') ?>
                <?= $form->field($customer_t, 'fullname')->label('ชื่อลูกค้า') ?>
                <?= $form->field($customer_t, 'address')->textArea()->label('ที่อยู่') ?>
                <?= $form->field($customer_t, 'phone')->label('โทรศัพท์') ?>
                <?= $form->field($customer_t, 'taxpayer_id')->label('เลขประจำตัวผู้เสียภาษี') ?>
                <div id="result"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" >เพิ่ม</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="container">
        <div class="col-sm-6">
            <label>เล่มที่ <span class="label label-default"><?= $book_number ?></span> ใบแจ้งหนี้ <span class="label label-default"><?=$invoiceId?></span> / ใบเสร็จ <span class="label label-default"><?=$receiptId?></span></label><br>
            <label>วันที่ <?=date('d/m/Y')?></label>
        </div>
        <div class="col-sm-6">
            <div class="pull-right">
                <a href="<?=Url::to(['invoice/search'])?>" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> ค้นหาใบแจ้งหนี้</a>
<!--
                <a href="<?=Url::to(['viecle/index'])?>" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> จัดการข้อมูลรถ </a>
-->
                <a id="btn-save-invoice" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> บันทึก</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
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
                                    'prompt' => 'เลือกทะเบียนรถ',
                                ]);?>
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
                    </form>
                </div>
                <div class="col-sm-6">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-3">เลขที่เคลม</label>
                            <div class="col-sm-9">
                                <input id="claim-no" class="form-control input-sm" value="<?= $invoice->claim['claim_no'] ?>">
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
                            <label class="control-label col-sm-3">ที่อยู่</label>
                            <div class="col-sm-9">
                                <textarea id="address" rows="3" class="form-control input-sm" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">เลขประจำตัวผู้เสียภาษี</label>
                            <div class="col-sm-9" id="tax">
                                <input id="tax-id" class="form-control input-sm" readonly>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered" id="tableInvoice">
            <thead>
                <tr bgcolor="#000000">
                    <th width="10%" class="text-white" style="color:white;">ลำดับ</th>
                    <th width="60%" style="color:white;">รายการ</th>
                    <th width="20%" style="color:white;">ราคา</th>
                    <th width="10%"></th>
                </tr>
                <tr id="input-row">
                    <td></td>
                    <td>
                        <input class="form-control" type="text" id="invoice-list" /> </td>
                    <td>
                        <input class="form-control" type="number" id="invoice-price" /> </td>
                    <td>
                        <button class="btn btn-primary btn-xs" id="btn-add-invoice"><span class="glyphicon glyphicon-plus"></span></button>
                    </td>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>จำนวนเงิน</td>
                    <td>
                        <div id="invoice-total"></div>
                        <div id="invoice-total-editing" style="display: none;">
                            <input type="text" class="form-control text-right">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ภาษีมูลค่าเพิ่ม (7%)</td>
                    <td>
                        <div id="invoice-tax"></div>
                        <div id="invoice-tax-editing" style="display: none;">
                            <input type="text" class="form-control text-right">
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ยอดรวมทั้งสิ้น</td>
                    <td>
                        <div id="invoice-grand-total"></div>
                        <div id="invoice-grand-total-editing" style="display: none;">
                            <input type="text" class="form-control text-right">
                        </div>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php 
$this->registerJS('$("#plate-no").select2();', VIEW::POS_READY);
?>