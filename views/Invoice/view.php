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
?>
    <div class="quotation-content">
        <table class="table table-bordered" id="myTable">
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
                        <div id="invoice-total"><?= number_format( $total, 2 ) ?></div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ภาษีมูลค่าเพิ่ม (7%)</td>
                    <td>
                        <div id="invoice-tax"><?= number_format( $vat, 2 ) ?></div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>ยอดรวมทั้งสิ้น</td>
                    <td>
                        <div id="invoice-grand-total"><?= number_format( $grandTotal, 2 ) ?></div>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
