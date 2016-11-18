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

<div class="row">
    <div class="container">
        <div class="col-sm-6">
            <label>เล่มที่ <span class="label label-default"><?= $receipt->book_number ?></span> ใบเสร็จ <span class="label label-default"><?=$receipt->reciept_id?></span></label><br>
            <label>วันที่ <?=date('d/m/Y')?></label>
        </div>
        <div class="col-sm-6">
            <div class="pull-right">
                <a href="<?=Url::to(['receipt/update-multiple-claim', 'rid' => $receipt->RID])?>" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> แก้ไขใบเสร็จ</a>
                <a href="<?=Url::to(['receipt/report', 'iid' => $receipt->IID])?>" target="_blank" class="btn btn-success btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> พิมพ์ใบเสร็จ</a>
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
<div>แก้ไขล่าสุด: <?=$lastUpdate->date?></div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <thead>
                <tr bgcolor="#000000">
                    <th width="10%" class="text-white" style="color:white;">ลำดับ</th>
                    <th width="70%" style="color:white;">รายการ</th>
                    <th width="20%" style="color:white;">ราคา</th>
                </tr>
            </thead>
            <tbody>
                <?php $num=1; foreach($descriptions as $description): ?>
                <tr>
                    <td class="text-center"><?=($num++)?></td>
                    <td><?=$description['description']?></td>
                    <td class="text-right"><?=number_format($description['price'], 2)?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>จำนวนเงิน</td>
                    <?php $total = $receipt->invoice->getInvoiceDescriptions()->sum('price')?>
                    <td><?=number_format($total, 2)?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ภาษีมูลค่าเพิ่ม (7%)</td>
                    <?php $vat = $total * 0.07 ?>
                    <td><?=number_format($vat, 2)?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ยอดรวมทั้งสิ้น</td>
                    <?php $grandTotal = $total+$vat?>
                    <td><?=number_format($grandTotal, 2)?></td>
                </tr>
            </tfoot>
        </table>
        
    </div>
</div>
