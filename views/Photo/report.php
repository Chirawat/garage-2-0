<?php
use yii\helpers\Html;
?>
<div style="text-align: center;">หมายเลขเคลม <?=$claim_no?> / <?=$type?></div>

<table width="100%">
    <?php $cnt=1; foreach($details as $detail): ?>
        <?php $images = explode(", ", $detail->filename); foreach($images as $image):?>
            <?php if($cnt % 2 == 1): ?>
                <tr>
                    <br>
                    <td style="padding: 10px; padding-top:50px;"><img src="upload/<?=$detail->CLID?>-<?=$detail->claim['claim_no']?>/<?=$detail->type?>/<?=$image?>" width="50%"></td>
            <?php else:?>
                    <br>
                    <td style="padding: 10px; padding-top:50px;"><img src="upload/<?=$detail->CLID?>-<?=$detail->claim['claim_no']?>/<?=$detail->type?>/<?=$image?>" width="50%"></td>
                </tr>
            <?php endif;?>
        <?php $cnt++; endforeach; 
    endforeach;?>
</table>
