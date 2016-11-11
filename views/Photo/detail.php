<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
?>
    <?php if(sizeof($details) == 0):?>
        ยังไม่มีรูปภาพ
        <?php else:?>
            <table class="table" width="100%">
                <caption>พบข้อมูล <?=sizeof($details)?> รายการ
                </caption>
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="50%">รูปภาพ</th>
                        <th width="15%">ลำดับ</th>
                        <th width="15%">จัดการรูปภาพ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach($details as $detail): ?>
                        <tr>
                            <th scope="row">
                                <?=($i++)?>
                            </th>
                            <td><img src="upload/<?=$detail->CLID?>-<?=$detail->claim['claim_no']?>/<?=$type?>/<?=$detail->filename?>" width="520px"></td>
                            <td>
                                <button class="btn btn-default"><span class="glyphicon glyphicon-arrow-up"></span></button>
                                <button class="btn btn-default"><span class="glyphicon glyphicon-arrow-down"></span></button>
                            </td>
                            <td>
                                <button class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></button>
                                <button class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
