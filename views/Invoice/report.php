<?php
use yii\helpers\Html;

function DateThai($strDate){
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    $strHour= date("H",strtotime($strDate));
    $strMinute= date("i",strtotime($strDate));
    $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม.","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];

//    return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
    return "$strDay $strMonthThai $strYear";
}
?>
    <table width="100%">
        <tr>
            <td width="10%">
                <?=Html::img(Yii::getAlias('@app').'/web/img/logo_t.jpg', ['width' => 60])?><br>
            </td>
            <td>
                <h3>ห้างหุ้นส่วนจำกัดยโสธรเจริญการช่าง</h3>
                <h3>YASOTHON JAROEN KARN CHANG LIMITED PARTNERSHIP</h3> 
                <small>345 ม.3 บ้านดอนมะยาง ต.ตาดทอง อ.เมืองยโสธร จ.ยโสธร 35000<br/>
                เบอร์โทรศัพท์ 045-712911, 099-2309916, 063-2362878 เบอร์แฟกซ์ 045-712911</small> 
            </td>
        </tr>
    </table>
    <br/>
    <h2 class="header">ใบแจ้งหนี้</h2>
    <table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0" style="border: 0px solid transparent;">
        <tr>
            <td width="70%" style="padding: 10px;"><b>ชื่อ <?= $invoice->customer->fullname ?></b>
                <br /> <b>ที่อยู่</b> <?= $invoice->customer->address ?>
                <?php if($invoice->customer->taxpayer_id != null): ?>
                <br /> เลขประจำตัวผู้เสียภาษีอากร <?= $invoice->customer->taxpayer_id ?></td>
                <?php endif; ?>
            <td width="30%" style="padding: 10px;">เล่มที่ <?= $invoice->book_number ?> เลขที่ <?= $invoice->invoice_id ?> <br>
                วันที่ <?= DateThai( $invoice->date ) ?>
                <br /> เลขประจำตัวผู้เสียภาษีอากร 0353556000391
                <br />  สาขา สำนักงานใหญ่</td>
        </tr>
    </table>
    <table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0" style="border: 0px solid transparent;">
        <tbody>
            <tr>
                <td width="10%" class="column-header">ที่</td>
                <td class="column-header">รายการ</td>
                <td width="30%" class="column-header">ราคา</td>
            </tr>
            <?php $i = 1; foreach( $descriptions as $description): ?>
            <tr>
                <td style="text-align: center;"><?= $i++ ?></td>
                <td><?= $description->description ?></td>
                <td style="text-align: right;"><?= number_format($description->price, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td>หมายเลขเคลม <?=$invoice->claim['claim_no']?></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>ทะเบียน <?=$invoice->viecle['plate_no']?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:right; border: 0px solid transparent;">จำนวนเงิน</td>
                <td style="text-align: right;"><?= number_format( $total, 2 ) ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:right; border: 0px solid transparent;">ภาษีมูลค่าเพิ่ม (7%)</td>
                <td style="text-align: right;"><?= number_format( $vat, 2 ) ?></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:right; border: 0px solid transparent;">ยอดรวมทั้งสิ้น</td>
                <td style="text-align: right;"><?= number_format( $grandTotal, 2 ) ?></td>
            </tr>
        </tbody>
    </table>

    <table class="table_bordered" width="70%" border="0" cellpadding="2" cellspacing="0" style="border: 0px solid transparent;">
        <tr>
            <td style="text-align: center; border: 0px solid transparent;">(ตัวอักษร)</td>
        </tr>
        <tr>
            <td style="text-align: center;" bgcolor="#b5b5b5"><?= $thbStr ?></td>
        </tr>
    </table>
    <br> ได้รับสินค้าข้างต้นถูกต้องแล้ว และเรียบร้อยแล้ว
    <br>
    <br> ขอบคุุณทุกท่านที่มาอุดหนุน
    <br> Thank You For Your Attention
    <br>
    <br>
    <table width="100%">
        <tr>
            <td width="50%"> ผู้รับเงิน_______________________ </td>
            <td width="50%"> ผู้จ่ายเงิน_______________________ </td>
        </tr>
    </table>
