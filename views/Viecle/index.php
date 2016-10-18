<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ViecleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
    <?php if( Yii::$app->request->get('status') == "failed" ) : ?>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissable"> <strong>พบข้อผิดพลาด!</strong> ไม่พบทะเบียนรถที่ระบุ</div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <?= Html::beginForm(['viecle/detail'], 'get') ?>
                        <div class="form-group">
                            <label class="control-label" for="plate_id">ทะเบียนรถ</label>
                            <input class="form-control" id="plate_id" name="plate_no" placeholder="ใส่ทะเบียนรถ" type="text"> </div>
                        <a href="<?= Url::to(['viecle/create']) ?>" class="btn btn-default">เพิ่ม</a>
                        <button type="submit" class="btn btn-default">ค้นหา</button>
                    <?= Html::endForm() ?>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
    </div>