<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
    <?php ActiveForm::begin([
    'options' => [
        'class' => 'form-inline'
    ]]) ?>
        <div class="form-group">
            <label>จาก</label>
            <?= Html::dropDownList('start-date', null, $receiptDate, ['class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <label>ถึง</label>
            <?= Html::dropDownList('end-date', null, $receiptDate, ['class' => 'form-control']) ?>
        </div>
        <button type="submit" class="btn btn-primary">ตกลง</button>
        <?php if($month != []): ?>
            <a target="_blank" href="<?=Url::to(['receipt/summary-report', 'startDate' => $startDate , 'endDate' => $endDate ])?>" class="btn btn-success">พิมพ์</a>
        <?php else: ?>
            <a class="btn btn-success">พิมพ์</a>
        <?php endif; ?>
        <?php ActiveForm::end(); ?>
        <br>


<?php if( isset($receipts) ): ?>
    <div class="alert alert-success" role="alert"><strong>ค้นพบ <?= sizeof($receipts) ?> รายการ</strong> จาก <?= $startDate ?> ถึง <?= $endDate ?></div>
    <?php foreach($month as $key => $values): ?>
        <?php $total = 0; ?>
        <label>ประจำเดือน <?= $key ?></label>
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
                <?php $i = 1; foreach($values as $value): ?>
                    <?php $total += $receipts[$value]->total; ?>
                    <tr>
                        <th scope="row"><?= ($i++) ?></th>
                        <td><?= $receipts[$value]->invoice['invoice_id'] ?></td>
                        <td><?= date('d-m-Y', strtotime($receipts[$value]->date)) ?></td>
                        <td><?= $receipts[$value]->invoice->customer['fullname']?></td>
                        <td><?= $receipts[$value]->invoice['claim_no'] ?></td>
                        <td><?= $receipts[$value]->invoice->viecle['plate_no'] ?></td>
                        <td class="text-right"><?= number_format($receipts[$value]->total, 2) ?></td>
                        <td><?= $receipts[$value]->employee['fullname']?></td>
                    </tr>
                <?php endforeach;  ?>
                <tr>
                    <td colspan="6" class="text-right">รวม</td>
                    <td class="text-right"><?= number_format($total,2) ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <br>
    <?php endforeach; ?>
<?php endif; ?>
