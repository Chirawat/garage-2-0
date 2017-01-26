<?php 
use yii\helpers\Html; 
?>
    <table width="100%">
        <tr>
            <td width="10%">
                <?=Html::img(Yii::getAlias('@app').'/web/img/logo_t.jpg', ['width' => 60])?><br>
            </td>
            <td>
                <h3>ห้างหุ้นส่วนจำกัดยโสธรเจริญการช่าง</h3>
                <h3>YASOTHON JAROEN KARN CHANG LIMITED PARTNERSHIP</h3> 
                <small>345 หมู่ 3 บ้านดอนมะยาง ตำบลตาดทอง อำเภอเมืองยโสธร จังหวัดยโสธร 35000<br/>
                เบอร์โทรศัพท์ 045-712911, 099-2309916, 063-2362878 เบอร์แฟกซ์ 045-712911</small> 
            </td>
        </tr>
    </table>
    <br/>
    <h2 class="header">ใบเสนอราคา</h2>
    <table width="100%">
        <tr>
            <td class="text-right"><b>เลขที่ <?= $model->quotation_id ?><br><br>วันที่ <?= DateThai($dt) ?></b></td>
        </tr>
        <tr>
            <td>
                <h3>
                    <?= $customerModel->fullname ?> เลขที่เคลม <?= $model->claim['claim_no'] ?>
                </h3>
            </td>
        </tr>
    </table>
<br/>
    <table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0" style="border: 0px solid transparent;">
        <tr>
            <td height="40" width="20%" class="column-header">ชื่อรถยนต์ / รุ่น</td>
            <td width="20%" class="column-header">เลขทะเบียน</td>
            <td width="35%" class="column-header">เลขตัวถัง</td>
            <td width="15%" class="column-header">เลขเครื่องยนต์</td>
            <td width="10%" class="column-header">ปีรุ่น</td>
        </tr>
        <tr>
            <td height="54" class="text-centered"><?= $viecleModel->viecleName['name'] ?> <br> <?= $viecleModel->viecleModel['model'] ?></td>
            <td class="text-centered"><?= $viecleModel->plate_no ?></td>
            <td class="text-centered"><?= $viecleModel->body_code ?></td>
            <td class="text-centered"><?= $viecleModel->engin_code ?></td>
            <td class="text-centered"><?= $viecleModel->viecle_year ?></td>
        </tr>
        
    </table>
<table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0" style="border: 0px solid transparent;">
    <tr>
        <td width="7%" height="40" class="column-header">ลำดับ</td>
        <td width="30%" class="column-header">รายการซ่อม</td>
        <td width="13%" class="column-header">ราคา</td>
        <td width="7%" class="column-header">ลำดับ</td>
        <td width="30%" class="column-header">รายการอะไหล่</td>
        <td width="13%" class="column-header">ราคา</td>
    </tr>
</table>