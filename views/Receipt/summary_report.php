<?php
use yii\helpers\Html;
?>

สรุปยอดการออกบิล ประจำเดือน ....
<table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0" style="border: 0px solid transparent;">
    <tr>
        <td width="5%" class="column-header">ลำดับ</td>
        <td width="10%" class="column-header">เลขที่ใบเสร็จ</td>
        <td width="12%" class="column-header">วันที่ออกใบเสร็จ</td>
        <td width="18%" class="column-header">ในนาม</td>
        <td width="15%" class="column-header">เลขที่เคลม</td>
        <td width="10%" class="column-header">ทะเบียน</td>
        <td width="10%" class="column-header">ยอดเงิน</td>
        <td width="12%" class="column-header">วันที่รับเงิน</td>
        <td width="8%" class="column-header">ผู้ออก</td>
        <td width="5%" class="column-header">หมายเหตุ</td>
    </tr>
    <?php $i = 1; foreach($receipts as $receipt): ?>
    <tr>
        <td style="text-align: center;"><?= ($i++) ?></td>
        <td style="text-align: center;"><?= $receipt->invoice['invoice_id'] ?></td>
        <td style="text-align: center;"><?= date('d-m-Y', strtotime($receipt->date)) ?></td>
        <td><?= $receipt->invoice->customer['fullname']?></td>
        <td><?= $receipt->invoice['claim_no'] ?></td>
        <td style="text-align: center;"><?= $receipt->invoice->viecle['plate_no'] ?></td>
        <td style="text-align: right;"><?= number_format($receipt->total,2) ?></td>
        <td>?</td>
        <td style="text-align: center;"><?= $receipt->employee['fullname']?></td>
        <td></td>
    </tr>
    <?php endforeach; ?>
</table>
