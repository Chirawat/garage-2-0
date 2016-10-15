<?php
// Car model
// http://www.carrecent.com/car-price
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\web\View;
$this->title = 'Viecles';
$this->params['breadcrumbs'][] = $this->title;
?>
    <?= Html::beginForm(['viecle/detail', 'plate_no' => Yii::$app->request->get('plate_no')], 'post', ['data-pjax' => '', 'class' => 'form-horizontal']); ?>
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
                    <!--                <form class="form-horizontal" role="form">-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เลขทะเบียน</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::input('text', 'plate_no', $model->plate_no, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">รุ่น</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeDropDownList($model, 'viecle_model', [], [
                                    'class' => 'form-control',
                                    'id' => 'viecle-model',
                                ]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เลขตัวถัง</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::input('text', 'body_code', $model->body_code, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">แบบตัวถัง</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::activeDropDownList($model, 'body_type', ArrayHelper::map( $bodyType, 'id', 'type'), ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">ที่นั่ง</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::input('text', 'seat', $model->seat, ['class' => 'form-control']) ?>
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
                                <?= Html::input('text', 'viecle_year', $model->viecle_year, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เลขเครื่อง</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::input('text', 'engin_code', $model->engin_code, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">ซีซี</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::input('text', 'cc', $model->cc, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">น้ำหนักรวม</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::input('text', 'weight', $model->weight, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <!--                </form>-->
                    <div class="col-md-6"></div>
                </div>
            </div>
        </div>
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3>ข้อมูลลูกค้า</h3> </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr> </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!--                    <form class="form-horizontal" role="form">-->
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="fullname" class="control-label">ชื่อลูกค้า</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::input('text', 'fullname', $model->owner0->fullname, ['id' => 'fullname', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">ที่อยู่</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::textarea('adress', $model->owner0->address, ['id' => 'address' , 'class' => 'form-control', 'rows' => 3]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เบอร์โทรศัพท์</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::input('text', 'phone', $model->owner0->phone, ['id' => 'phone', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label for="inputEmail3" class="control-label">เบอร์แฟกซ์</label>
                            </div>
                            <div class="col-sm-9">
                                <?= Html::input('text', 'fax', $model->owner0->fax, ['id' => 'fax' , 'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <!--                    </form>-->
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group"> <a href="#modal1" data-toggle="modal" class="btn btn-primary">แก้ไข</a> <a href="<?=Url::to(['quotation/index'])?>" class="btn btn-primary">เสร็จสิ้น</a> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section">
            <div id="modal1" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">ยืนยันการแก้ไข</h4> </div>
                        <div class="modal-body">
                            <div class="container">
                                <h4>ข้อมูลรถ</h4>
                                <p>เลขทะเบียน <strong>####</strong></p>
                                <p>ชื่อรถ <strong>####</strong>  รุ่น <strong>####</strong> ปี <strong>####</strong></p>
                                <p>เลขตัวถัง <strong>####</strong>  เลขเครื่อง <strong>####</strong> แบบตัวถัง <strong>####</strong></p>
                                <p>ซีซี <strong>####</strong>  ที่นั่ง <strong>####</strong> น้ำหนักรวม <strong>####</strong></p>
                                <h4>ข้อมูลลูกค้า</h4>
                                <p>ชื่อลูกค้า <strong id="fullname_t"><?= $model->owner0->fullname ?></strong></p>
                                <p>ที่อยู่ <strong id="address_t"><?= $model->owner0->address ?></strong></p>
                                <p>เบอร์โทรศัพท์ <strong id="phone_t"><?= $model->owner0->phone ?></strong> เบอร์แฟ็กซ์ <strong id="fax_t"><?= $model->owner0->fax ?></strong></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">ยืนยันการแก้ไข</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        <?= Html::endForm() ?>

<?php
// copy info into confirmation modal
// viecle -------------------------------------------
    
//customer-------------------------------------------
$this->registerJs('$("#fullname").keyup(function(){ $("#fullname_t").text( $("#fullname").val() )});', View::POS_READY);
$this->registerJs('$("#address").keyup(function(){ $("#address_t").text( $("#address").val() )});', View::POS_READY);
$this->registerJs('$("#phone").keyup(function(){ $("#phone_t").text( $("#phone").val() )});', View::POS_READY);
$this->registerJs('$("#fax").keyup(function(){ $("#fax_t").text( $("#fax").val() )});', View::POS_READY);
?>