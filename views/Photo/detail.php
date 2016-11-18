<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?=Html::beginForm(['photo/del', 'get'])?>
<?php if(sizeof($details) != 0): ?>
    <div class="form-group">
        <input type="hidden" name="CLID" value="<?=$details[0]->CLID?>">
        <input type="hidden" name="type" value="<?=$details[0]->type?>">
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
                
            
                <?php $images = explode(", ", $detail->filename); foreach($images as $image):?>
                <div class="col-md-3 portfolio-item">
                    <?php $file = "upload/" . $detail->CLID . "-" . $detail->claim['claim_no'] . "/" . $detail->type . "/" . $image; ?>
                    <a href="<?=$file?>" target="_blank">
                        <img class="img-responsive" src= "<?=$file?>" alt="">
                    </a>
                    <?=Html::checkbox('filename[]', false, ['label' => $image, 'value' => $image])?>
                    <input type="hidden" name="PID" value="<?=$detail->PID?>">
                </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?=Html::endForm()?>
        <!-- /.row -->
