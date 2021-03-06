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
            <label>เล่มที่ <span class="label label-default"><?= $book_number ?></span> ใบเสร็จ <span class="label label-default"><?=$receiptId?></span></label><br>
            <label>วันที่ <?=date('d/m/Y')?></label>
        </div>
        <div class="col-sm-6">
            <div class="pull-right">
                <a href="<?=Url::to(['receipt/search'])?>" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> ค้นหาใบเสร็จ</a>
                <a href="<?=Url::to(['viecle/index'])?>" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> จัดการข้อมูลรถ </a>
                <a id="btn-save-multiple" class="btn btn-primary btn-sm">
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
                                <div class="form-group">
                                <?= Html::dropDownList('multiple-claim-no', null, ArrayHelper::map($claim, 'CLID', 'claim_no'), [
                                        'class' => 'form-control input-sm',
                                        'id' => 'multiple-claim-no',
                                        'prompt' => 'ไม่ระบุ']);?>
                                </div>
                                <div class="form-group">
                                    <a id="add-claim-no" class="btn btn-primary btn-sm">เพิ่ม</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">เคลมที่เลือก</label>
                            <div id="customer-list" class="col-sm-9">
                                <div class="form-group">
                                    <select id="selected-claim" multiple class="form-control">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <a id="remove-selected-claim" class="btn btn-danger btn-sm">ลบที่เลือก</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-3">บริษัทประกัน</label>
                            <div id="customer-list" class="col-sm-9">
                                <?= Html::dropDownList('insurance-company', null, ArrayHelper::map($insuranceCompany, 'CID', 'fullname'), [
                                    'prompt' => 'เลือกรายการ',
                                    'class' => 'form-control input-sm',
                                    'id' => 'insurance-company']);?>
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
<?php
$this->registerJS('$("#plate-no").select2();', View::POS_READY);
//$this->registerJS('$("#customer").select2();', View::POS_READY);

$str = 'function updateViecleDetail(data){
            $("#viecle-name").val( data.viecle_name );
            $("#viecle-model").val( data.viecle_model );
            $("#year").val( data.year );
            $("#engine-code").val( data.engine_code );
            $("#body-code").val( data.body_code );
}';
$this->registerJS( $str, View::POS_BEGIN);
$this->registerJS( $str, View::POS_BEGIN);
?>
