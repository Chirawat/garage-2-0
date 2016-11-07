<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = "ค้นหาใบเสนอราคา";
?>
<div class="col-sm-4">
    <?php ActiveForm::begin(['options' => ['class' => 'form-inline']]); ?>

    <div class="form-group">
        <label>ชื่อลูกค้า</label>
        <?= Html::input('text', 'fullname', '', ['class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">ค้นหา</button>
    </div>
    <?php ActiveForm::end(); ?>
    <br>
</div>
<?php if( isset($customers) && sizeof($customers) > 0 ): ?>
    <div class="col-sm-12">
        <div class="alert alert-success" role="alert"> พบข้อมูลจำนวน <?= sizeof($customers) ?> รายการ </div>
    </div>
    <div class="col-sm-12">
        <table width="100%" class="table table-hover" id="search-table">
            <tbody>
                <tr>
                    <th>#</th>
                    <th>ชื่อลูกค้า</th>
                    <th>จำนวน</th>
                    <th>ข้อมูลล่าสุด</th>
                </tr>
                <?php foreach($customers as $customer): ?>
                    <tr data-href="<?= Url::to(['invocie/view', 'iid' => 0 ]) ?>" style="cursor: pointer;">
                        <td></td>
                        <td> <?= $customer->fullname ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>


<!--In case of not found-->
<?php if(isset($status) && $status == 'failed'):?>
    <div class="col-sm-12">
        <div class="alert alert-danger" role="alert"> <strong>ไม่พบข้อมูล!</strong> ไม่มีประวัติรถในฐานข้อมูล </div>
    </div>
<?php endif; ?>

<?php
$this->registerJs('$("table#search-table tbody tr").click(function(){ window.document.location = $(this).data("href")});', VIEW::POS_READY);
?>
