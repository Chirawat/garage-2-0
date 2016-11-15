<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Viecle */

$this->title = 'เพิ่มข้อมูลรถ';
?>
<?php $form = ActiveForm::begin(['options' => [ 'class' => 'form-horizontal']]) ?>
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3>ข้อมูลรถ</h3> </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr> </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เลขทะเบียน</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $model, 'plate_no', [
                                    'id' => 'plate-no',
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">รุ่น</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeDropDownList($model, 'viecle_model', ArrayHelper::map($viecleModels, 'id', 'model'), [
                                    'class' => 'form-control',
                                    'id' => 'viecle-model',]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เลขตัวถัง</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $model, 'body_code', [
                                    'id' => 'body-code',
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">แบบตัวถัง</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $model, 'body_type', [
                                    'id' => 'body-code',
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">ที่นั่ง</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $model, 'seat', [
                                    'id' => 'seat',
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">ชื่อรถ</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeDropDownList($model, 'viecle_name', ArrayHelper::map($viecleName, 'id', 'name') , [
                                    'id' => 'vielce-name',
                                    'class' => 'form-control',
                                    'onchange' => '$.post("'. Url::to(['viecle/model-list']) .'", {viecleNameId: $(this).val()}, function(data){ $("select#viecle-model").html( data ) });',
                                ]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">ปี</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $model, 'viecle_year', [
                                    'id' => 'viecle-year',
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เลขเครื่อง</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $model, 'engin_code', [
                                    'id' => 'engin-code',
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">ซีซี</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $model, 'cc', [
                                    'id' => 'cc',
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">น้ำหนักรวม</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $model, 'weight', [
                                    'id' => 'weight',
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            </div>
        </div>
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3>ข้อมูลผู้เอาประกัน</h3> </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr> </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="fullname" class="control-label">ชื่อ</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $customer, 'fullname', [
                                    'id' => 'fullname', 
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">ที่อยู่</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeTextarea( $customer, 'address', [
                                    'id' => 'address' , 
                                    'class' => 'form-control', 
                                    'rows' => 3]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เบอร์โทรศัพท์ 1</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $customer, 'phone', [
                                    'id' => 'phone', 
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เบอร์โทรศัพท์ 2</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text', $customer, 'phone2', [
                                    'id' => 'fax' , 
                                    'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <div class="row">
                    <div class="container">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <button type="submit" class="btn btn-success">เสร็จสิ้น</button> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end() ?>