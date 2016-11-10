<?php
use yii\helpers\Html;
?>
<div style="text-align: center;">
    <b>สรุปยอดการออกบิล</b>
</div>
<?php foreach($month as $key => $values): ?>
<?php $total = 0; ?>
ประจำเดือน <?= $key ?>
<table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0" style="border: 0px solid transparent;">
    <tr>
        <td width="5%" class="column-header">ลำดับ</td>
        <td width="10%" class="column-header">เลขที่ใบเสร็จ</td>
        <td width="10%" class="column-header">วันที่ออกใบเสร็จ</td>
        <td width="20%" class="column-header">ในนาม</td>
        <td width="15%" class="column-header">เลขที่เคลม</td>
        <td width="10%" class="column-header">ทะเบียน</td>
        <td width="10%" class="column-header">ยอดเงิน</td>
<!--        <td width="12%" class="column-header">วันที่รับเงิน</td>-->
        <td width="10%" class="column-header">ผู้ออก</td>
        <td width="5%" class="column-header">หมายเหตุ</td>
    </tr>
    <?php $i = 1; foreach($values as $value): ?>
    <?php $total += $receipts[$value]->total; ?>
    <tr>
        <td style="text-align: center;"><?= ($i++) ?></td>
        <td style="text-align: center;"><?= $receipts[$value]->invoice['invoice_id'] ?></td>
        <td style="text-align: center;"><?= date('d-m-Y', strtotime($receipts[$value]->date)) ?></td>
        <td><?= $receipts[$value]->invoice->customer['fullname']?></td>
        <td><?= $receipts[$value]->invoice->claim['claim_no'] ?></td>
        <td style="text-align: center;"><?= $receipts[$value]->invoice->viecle['plate_no'] ?></td>
        <td style="text-align: right;"><?= number_format($receipts[$value]->total,2) ?></td>
        <td style="text-align: center;"><?= $receipts[$value]->employee['fullname']?></td>
        <td></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="6" style="text-align: right; border: 0px;">รวม</td>
        <td style="text-align: right; border: 3px none; border-bottom: 3px double;" > <?= number_format($total,2) ?></td>
    </tr>
</table>
<br>
<?php endforeach; ?>
