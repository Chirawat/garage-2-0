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
?>
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

<div class="row">
    <div class="container">
        <div class="col-sm-6">
            <label>เล่มที่ <span class="label label-default"><?= $receipt->book_number ?></span> ใบเสร็จ <span class="label label-default"><?=$receipt->reciept_id?></span></label><br>
            <label>วันที่ <?=date('d/m/Y')?></label>
        </div>
        <div class="col-sm-6">
            <div class="pull-right">
                <a id="update-multiple-claim" class="btn btn-primary btn-sm">
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
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-3">เลขที่เคลม</label>
                            <div class="col-sm-9">
                                <select multiple class="form-control" readonly>
                                <?php foreach($receipt->paymentStatus as $CLID): ?>
                                    <option><?=$CLID->claim['claim_no']?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-3">บริษัทประกัน</label>
                            <div id="customer-list" class="col-sm-9">
                                <input type="text" class="form-control" value="<?=$receipt->invoice->customer['fullname']?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">ที่อยู่</label>
                            <div class="col-sm-9">
                                <textarea id="address" rows="3" class="form-control" readonly><?=$receipt->invoice->customer['address']?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">เลขประจำตัวผู้เสียภาษี</label>
                            <div class="col-sm-9" id="tax">
                                <input id="tax-id" class="form-control" value="<?=$receipt->invoice->customer['taxpayer_id']?>" readonly>
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
<div>แก้ไขล่าสุด: <?=$lastUpdate->date?></div>

<?php foreach($descriptions as $invoiceDescription){
    $this->registerJS('invoice.push({
        list: ' . json_encode($invoiceDescription->description, JSON_HEX_TAG) . ',
        price: ' . json_encode($invoiceDescription->price, JSON_HEX_TAG) . '});', VIEW::POS_END);
}
$this->registerJS('rederTableInvoice();calTotalInvoice();', VIEW::POS_READY);
?>

