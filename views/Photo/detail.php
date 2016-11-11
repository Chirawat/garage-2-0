<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?=Html::beginForm(['photo/del', 'get'])?>
<?php if(sizeof($details) != 0): ?>
    <div class="form-group">
        <button type="submit" class="btn btn-danger">ลบที่เลือก</button>
    </div>
<?php else:?>
<div class="alert alert-danger">
  <strong>ไม่พบข้อมูล</strong>
</div>
<?php endif; ?>
<!-- Projects Row -->
        <div class="row">
            <?php foreach($details as $detail): ?>
                <div id="open-<?=$detail->PID?>" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">ดูรูปภาพ</h4>
                            </div>
                        <div class="modal-body">
                            <div>
                                <img src="upload/<?=$detail->CLID?>-<?=$detail->claim['claim_no']?>/<?=$detail->type?>/<?=$detail->filename?>" alt="" width="80%">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <div class="col-md-3 portfolio-item">
                    <a href="#open-<?=$detail->PID?>" data-toggle="modal">
                        <img class="img-responsive" src="upload/<?=$detail->CLID?>-<?=$detail->claim['claim_no']?>/<?=$detail->type?>/<?=$detail->filename?>" alt="">
                    </a>
                    <?=Html::checkbox('PID[]', false, ['label' => $detail->filename, 'value' => $detail->PID])?>
                </div>
            <?php endforeach; ?>
        </div>
    <?=Html::endForm()?>
        <!-- /.row -->
