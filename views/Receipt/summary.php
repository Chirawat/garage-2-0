<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
    <?php ActiveForm::begin([
    'options' => [
        'class' => 'form-inline'
    ]]) ?>
        <div class="form-group">
            <label>จาก</label>
            <?= Html::dropDownList('start-date', null, ArrayHelper::getColumn($receiptDate, 'dt'), ['class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <label>ถึง</label>
            <?= Html::dropDownList('end-date', null, ArrayHelper::getColumn($receiptDate, 'dt'), ['class' => 'form-control']) ?>
        </div>
        <button type="submit" class="btn btn-primary">ตกลง</button>
        <a target="_blank" href="<?=Url::to(['receipt/summary-report'])?>" class="btn btn-success">พิมพ์</a>
        <?php ActiveForm::end(); ?>
        <br>


<?php if( isset($receipts) ): ?>
    <label>ประจำเดือน</label>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>เลขที่ใบเสร็จ</th>
                <th>วันที่ออกใบเสร็จ</th>
                <th>ในนาม</th>
                <th>เลขที่เคลม</th>
                <th>เลขทะเบียน</th>
                <th>ยอดเงิน</th>
                <th>ผู้ออก</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($receipts as $receipt): ?>
            <tr>
                <th scope="row"><?= ($i++) ?></th>
                <td><?= $receipt->invoice['invoice_id'] ?></td>
                <td><?= date('d-m-Y', strtotime($receipt->date)) ?></td>
                <td><?= $receipt->invoice->customer['fullname']?></td>
                <td><?= $receipt->invoice['claim_no'] ?></td>
                <td><?= $receipt->invoice->viecle['plate_no'] ?></td>
                <td><?= number_format($receipt->total,2) ?></td>
                <td><?= $receipt->employee['fullname']?></td>
                <td></td>
                <td></td>
            </tr>
            <?php endforeach;  ?>
        </tbody>
    </table>
<?php endif; ?>
