<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = "ค้นหาใบเสนอราคา";
?>
<div class="col-sm-4">
    <?php ActiveForm::begin(); ?>
    
    <div class="form-group">
        <label>เลขทะเบียน</label>
        <?= Html::input('text', 'plate_no', '', ['class' => 'form-control']) ?>
    </div>
    
    <div class="form-group">
        <label>เลขที่ใบเสนอราคา</label>
        <?= Html::input('text', 'quotation_id', null, ['class' => 'form-control']) ?>
    </div>
    
    <button type="submit" class="btn btn-primary">ค้นหา</button>
    <?php ActiveForm::end(); ?>
</div>
<?php if( isset($quotations) && sizeof($quotations) > 1 ): ?>
    <div class="col-sm-12">
        <table width="100%" class="table table-hover" id="search-table">
            <tbody>
                <tr>
                    <th width="10%">#</th>
                    <th width="20%">เลขที่เคลม</th>
                    <th width="20%">วันที่</th>
                </tr>
                <?php foreach($quotations as $quotation): ?>
                    <tr data-href="<?= Url::to(['quotation/view', 'qid' => $quotation->QID ]) ?>" style="cursor: pointer;">
                        <td></td>
                        <td> <?= $quotation->claim_no ?></td>
                        <td> <?= $quotation->quotation_date ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
$this->registerJs('$("table#search-table tbody tr").click(function(){ window.document.location = $(this).data("href")});', VIEW::POS_READY);
?>