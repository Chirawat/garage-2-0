<?php
use yii\widgets\ActiveForm;

$this->title = "เพิ่มรูปภาพ";
?>
<div class="pull-right">
    <a href="#" class="btn btn-success">พิมพ์หน้าปัจจุบัน</a>
    <a href="#" class="btn btn-success">พิมพ์ทั้งหมด</a>
</div>
    <?php ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
        <div class="form-group">
            <label class="control-label col-sm-1">เลขที่เคลม</label>
            <div class="col-sm-3">
                <input type="text" class="form-control">
            </div>
            <button class="btn btn-primary">เพิ่ม</button>
        </div>
        <br>
        <div class="form-group">
            <label class="control-label col-sm-1">ช่วงเวลา</label>
            <div class="col-sm-3">
                <select class="form-control">
                    <option>ภาพก่อนซ่อม</option>
                    <option>ภาพขณะซ่อม</option>
                    <option>เทียบอะไหล่</option>
                    <option>ซ่อมเสร็จ</option>
                    <option>อื่น ๆ</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="exampleInputFile" class="control-label col-sm-1">ไฟล์</label>
            <div class="col-sm-3">
                <input type="file" id="exampleInputFile">
                <p class="help-block">ไฟล์ jpg ขนาดไม่เกิน 10 MB</p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-3">
                <button type="submit" class="btn btn-primary">upload</button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
            <div>
                <table class="table" width="100%">
                    <caption>###### ของ ######## จำนวน X รูปภาพ</caption>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="50%">รูปภาพ</th>
                            <th width="15%">ลำดับ</th>
                            <th width="15%">จัดการรูปภาพ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td><img src="upload/sample/home-3" width="500"></td>
                            <td>
                                <button class="btn btn-default"><span class="glyphicon glyphicon-arrow-up"></span></button>
                                <button class="btn btn-default"><span class="glyphicon glyphicon-arrow-down"></span></button>
                            </td>
                            <td>
                                <button class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></button>
                                <button class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">1</th>
                            <td><img src="upload/sample/sample.jpg" width="500"></td>
                            <td>เลื่อนขึ้น / เลื่อนลง</td>
                            <td>Edit / Del</td>
                        </tr>
                    </tbody>
                </table>
            </div>
