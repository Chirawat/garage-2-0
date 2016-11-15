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
use yii\web\View;

$this->title = 'ใบเสร็จ';

$url = Url::to(['customer-list']);
?>
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
    <div class="row">
        <div class="container">
            <div class="col-sm-6">
                <label>
                    ใบแจ้งหนี้ <span class="label label-default"><?=$invoice->book_number?> - <?=$invoice->invoice_id?></span> 
                    <?php if( isset($invoice->reciept['reciept_id']) ): ?>
                        / ใบเสร็จ <span class="label label-default"><?=$invoice->reciept['book_number']?>-<?=$invoice->reciept['reciept_id']?></span>
                    <?php endif;?>
                </label><br>
                <label>วันที่ <?=date('d/m/Y')?></label>
            </div>
            <div class="col-sm-6">
                <div class="pull-right">
                    <a id="btn-edit-invoice" class="btn btn-primary btn-sm">
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
                                    <input type="text" class="form-control input-sm" value="<?=$viecle->plate_no?>" readonly>
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
                                    <input id="claim-no" class="form-control input-sm" value="<?= $invoice->claim['claim_no'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">ประเภทลูกค้า</label>
                                <div class="col-sm-9">
                                    <label class="radio-inline">
                                        <input type="radio" name="customer-type" id="customer-type" value="GENERAL" <?=$customer->type=="GENERAL"?"checked":null?>> ลูกค้าทั่วไป </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="customer-type" id="customer-type" value="INSURANCE_COMP" <?=$customer->type=="INSURANCE_COMP"?"checked":null?>> บริษัทประกัน </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">นามลูกค้า</label>
                                <div id="customer-list" class="col-sm-9">
                                    <input type="text" class="form-control input-sm" value="<?=$customer->fullname?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">ที่อยู่</label>
                                <div class="col-sm-9">
                                    <textarea id="address" rows="3" class="form-control input-sm" readonly><?=$customer->address?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">เลขประจำตัวผู้เสียภาษี</label>
                                <div class="col-sm-9" id="tax">
                                    <input id="tax-id" class="form-control input-sm" readonly value="<?=$customer->taxpayer_id?>">
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
                        <button class="btn btn-primary btn-xs" id="btn-add-invoice">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </td>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>จำนวนเงิน</td>
                    <td><div id="invoice-total"></div></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ภาษีมูลค่าเพิ่ม (7%)</td>
                    <td><div id="invoice-tax"></div></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ยอดรวมทั้งสิ้น</td>
                    <td><div id="invoice-grand-total"></div></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php foreach($invoiceDescriptions as $invoiceDescription){
    $this->registerJS('invoice.push({
        list: ' . json_encode($invoiceDescription->description, JSON_HEX_TAG) . ',
        price: ' . json_encode($invoiceDescription->price, JSON_HEX_TAG) . '});', VIEW::POS_END);
}
$this->registerJS('rederTableInvoice();calTotalInvoice();', VIEW::POS_READY);
?>
