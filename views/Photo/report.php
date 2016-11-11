<?php
use yii\helpers\Html;
?>
<div style="text-align: center;">หมายเลขเคลม <?=$claim_no?> / <?=$type?></div>

<table width="100%">
    <?php $cnt=1; foreach($details as $detail): ?>
        <?php if($cnt % 2 == 1): ?>
            <tr>
                <td style="padding: 10px;"><img src="upload/<?=$detail->CLID?>-<?=$detail->claim['claim_no']?>/<?=$detail->type?>/<?=$detail->filename?>" width="40%"></td>
        <?php else:?>
                <td style="padding: 10px;"><img src="upload/<?=$detail->CLID?>-<?=$detail->claim['claim_no']?>/<?=$detail->type?>/<?=$detail->filename?>" width="40%"></td>
            </tr>
        <?php endif;?>
    <?php $cnt++; endforeach; ?>
</table>
