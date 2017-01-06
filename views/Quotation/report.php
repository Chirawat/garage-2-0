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
    return "$strDay $strMonthThai $strYear";
}
?>
    <table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0" style="border: 0px solid transparent;">
        
        <?php for($i = 0; $i < $numRow; $i++):?>
        <tr>
            <td width="7%" style="text-align: center;"><?= isset($maintenanceDescriptionModel[$i]) ? ($i + 1): null ?></td>
            <td width="30%" class="description"><?= isset($maintenanceDescriptionModel[$i]) ? $maintenanceDescriptionModel[$i]->description:null ?></td>
            <td width="13%" class="text-right"><?= isset($maintenanceDescriptionModel[$i]) ? number_format( $maintenanceDescriptionModel[$i]->price, 2):null ?></td>
            <td width="7%" style="text-align: center;"><?= isset($partDescriptionModel[$i]) ? ($i + 1):null ?></td>
            <td width="30%" class="description"><?= isset($partDescriptionModel[$i]) ? $partDescriptionModel[$i]->description:null ?></td>
            <td width="13%" class="text-right"><?= isset($partDescriptionModel[$i]) ? number_format( $partDescriptionModel[$i]->price, 2 ):null ?></td>
        </tr>
       <?php endfor; ?>
        <tr>
            <td class="total-cell"></td>
            <td class="text-right"><b>รวมรายการซ่อม</b></td>
            <td class="text-right"><b><?= number_format( $sumMaintenance, 2) ?></b></td>
            <td class="total-cell"></td>
            <td class="text-right"><b>รวมรายการอะไหล่</b></td>
            <td class="text-right"><b><?= number_format( $sumPart, 2 ) ?></b></td>
        </tr>
        <tr>
            <td class="total-cell" colspan="4" style="border: 0px solid transparent;"></td>
            <td class="text-right"><b>รวมสุทธิ</b></td>
            <td class="text-right"><b><?= number_format( $total , 2) ?></b></td>
        </tr>
    </table>
    <br/>
    <br/>
    <br/>
