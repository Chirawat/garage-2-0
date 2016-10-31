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

$this->title = 'ใบเสร็จ';

$url = Url::to(['customer-list']);

Modal::begin([
    'id' => 'modal-save',
    'header' => '<h5>การบันทึก</h5>',
]);

echo 'บันทึกข้อมูลเรียบร้อย';

Modal::end();
?>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
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
                    <div id="result">
                        result here
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="row">
        <div class="container col-sm-6">
            <form class="form-horizontal">
                <div class="form-group">
                    <div class="col-sm-2 pull-left">
                        <label class="control-label">เลขที่</label>
                    </div>
                    <div class="col-sm-6">
                        <?php if( Yii::$app->request->get('quotation_id') != null ): ?>
                            <!--View-->
                            <input id="invoiceId" type="text" class="form-control col-sm-6">
                            <?php else: ?>
                                <!--New-->
                                <input id="invoiceId" type="text" value="<?= $iid ?>" class="form-control col-sm-6">
                                <?php endif; ?>
                    </div> <a id="viewInovoice" class="btn btn-primary disabled"><span class="glyphicon glyphicon-search"></span></a> <a href="<?=Url::to(['invoice/create'])?>" class="btn btn-primary"><span class="glyphicon glyphicon-file"></span></a> </div>
            </form>
        </div>
        <div class="container col-sm-6">
            <div class="pull-right"> <a id="btn-save-invoice" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-save-file"></span> บันทึก</a> <a href="<?= Url::to(['invoice/invoice-report', 'invoice_id'=> Yii::$app->request->get('invoice_id'), 'iid'=> Yii::$app->request->get('iid')]) ?>" id="btn-print-invoice" target="_blank" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-print"></span> พิมพ์ใบเสร็จ</a> </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลลูกค้า</h3> </div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <lable class="col-sm-4" for="customer">ลูกค้า</lable>
                            <input type="text"> </div>
                        <div class="form-group">
                            <lable class="col-sm-4" for="customer">ที่อยู่</lable>
                            <input type="text"> </div>
                        <div class="form-group">
                            <lable class="col-sm-4" for="customer">เลขประจำตัวผู้เสียภาษี</lable>
                            <input type="text"> </div>
                    </form>
                    <button data-toggle="modal" data-target="#myModal">ค้นจากรายชื่อลูกค้า</button>
                </div>
            </div>
        </div>
        <?= $detail ?>
