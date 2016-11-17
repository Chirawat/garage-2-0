<?php
// http://www.bsourcecode.com/2013/04/yii-ajax-request-and-json-response-array/
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\jui\AutoComplete;
use yii\helpers\ArrayHelper;

$this->title = "ใบแจ้งหนี้";
?>
    <div class="row">
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
                <a href="<?=Url::to(['invoice/edit', 'iid'=>$invoice->IID])?>" id="btn-edit-invoice" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> แก้ไข</a> 
                <a id="btn-print-invoice" target="_blank" class="btn btn-success btn-sm">
                    <span class="glyphicon glyphicon-print"></span> พิมพ์ใบแจ้งหนี้ </a>
                <a id="btn-print-receipt" target="_blank" class="btn btn-success btn-sm">
                    <span class="glyphicon glyphicon-print"></span> พิมพ์ใบเสร็จ </a>
            </div>
        </div>
    </div>

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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                                        <input type="radio" name="customer-type" id="customer-type" value="GENERAL" <?=$customer->type=="GENERAL"?"checked":null?> disabled> ลูกค้าทั่วไป </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="customer-type" id="customer-type" value="INSURANCE_COMP" <?=$customer->type=="INSURANCE_COMP"?"checked":null?> disabled> บริษัทประกัน </label>
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
    <?php Pjax::begin(); ?>
    <div class="quotation-content">
        <div class="form-group">
            <?php $form = ActiveForm::begin(['options' => [
                'data-pjax' => true,
                'class' => 'form-inline']]) ?>
                <label class="form-label">ประวัติการแก้ไข</label>
                <select id="history-date" name="dateIndex" class="form-control input-sm">
                    <div class="col-sm-3">
                        <?php $i= 0; foreach($dateLists as $date): ?>
                            <option value="<?= $i ?>" <?= $i==$dateIndex?"selected":null?> >
                                <?= $date->date ?> <?= $i==0 ? "(ล่าสุด)":null ?>
                            </option>
                        <?php $i++; endforeach; ?>
                    </div>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">ค้น</button>
            <?php ActiveForm::end() ?>

        </div>
        <table class="table table-bordered" id="viewInvoice">
            <thead>
                <tr bgcolor="#000000">
                    <th class="col-sm-1" class="text-white" style="color:white;">ลำดับ</th>
                    <th class="col-sm-9" style="color:white;">รายการ</th>
                    <th class="col-sm-2" style="color:white;">ราคา</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach($descriptions as $description):?>
                    <tr>
                        <td style="text-align: center;"><?= $i++ ?></td>
                        <td><?= $description->description ?></td>
                        <td style="text-align: right;"><?= number_format($description->price, 2) ?></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>จำนวนเงิน</td>
                    <td>
                        <div><?= number_format( $total, 2 ) ?></div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ภาษีมูลค่าเพิ่ม (7%)</td>
                    <td>
                        <div><?= number_format( $vat, 2 ) ?></div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ยอดรวมทั้งสิ้น</td>
                    <td>
                        <div><?= number_format( $grandTotal, 2 ) ?></div>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <?php Pjax::end() ?>
    </div>
