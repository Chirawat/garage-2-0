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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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
        <div class="container col-sm-6">
            <div class="col-sm-6">
                <label>เล่มที่ ...... เลขที่ ..............</label><br>
                <label>วันที่ ..............</label>
            </div>
        </div>
        <div class="container col-sm-6">
            <div class="pull-right">
                <a href="<?=Url::to(['invoice/search'])?>" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> ค้นหาใบแจ้งหนี้</a>
                <a id="btn-save-invoice" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> บันทึก</a>
            </div>
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
                            <lable class="control-label col-sm-2" for="customer">ลูกค้า</lable>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" value="<?=$customer->fullname ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <lable class="control-label col-sm-2" for="customer">ที่อยู่</lable>
                            <div class="col-sm-10">
                                <textarea rows="3" class="form-control input-sm"><?=$customer->address?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <lable class="control-label col-sm-2" for="customer">โทร</lable>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" value="<?=$customer->phone?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#customerSearch">ค้นจากรายชื่อลูกค้า</a>
                                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#customerCreate">เพิ่มลูกค้า</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered" id="tableInvoice">
            <thead>
                <tr bgcolor="#000000">
                    <th class="col-sm-1" class="text-white" style="color:white;">ลำดับ</th>
                    <th class="col-sm-9" style="color:white;">รายการ</th>
                    <th class="col-sm-2" style="color:white;">ราคา</th>
                    <th></th>
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
