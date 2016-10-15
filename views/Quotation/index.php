<?php
use yii\jui\Dialog;
use yii\bootstrap\Modal;
use yii\helpers\Url;
?>
    <div class="modal fade" id="customer" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modal title</h4> </div>
                <div class="modal-body">
                    <select multiple class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="container">
        <form class="form-inline">
            <div class="form-group">
                <label class="form-label">เลขที่</label>
                <input type="text" class="form-control input-sm" readonly value="1/2559"> </div>
            <div class="form-group">
                <label class="form-label">วันที่</label>
                <input type="text" class="form-control input-sm" readonly value="1/10/2559"> </div>
            <div class="form-group pull-right"> 
                <a href="<?=Url::to(['viecle/index'])?>" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> จัดการข้อมูลรถ
                </a> 
                |
                <a id="btn-save" class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-save-file"></span> บันทึก
                </a> 
                <a id="btn-print" target="_blank" class="btn btn-success btn-sm">
                    <span class="glyphicon glyphicon-print"></span> พิมพ์ใบเสนอราคา
                </a> 
            </div>
        </form>
    </div>
    <br>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-6">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ทะเบียน</label>
                            <div class="col-sm-4">
                                <select type="text" class="form-control input-sm"> </select>
                            </div>
                            <label class="col-sm-2 control-label">ชื่อรถ</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" readonly> </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">รุ่น</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" readonly> </div>
                            <label class="col-sm-2 control-label">ปี</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" readonly> </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">เลขตัวถัง</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" readonly> </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">เลขเครื่อง</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" readonly> </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="customer">ชื่อ</label>
                            <div class="col-sm-9">
                                <input class="form-control input-sm" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="adress">ที่อยู่</label>
                            <div class="col-sm-9">
                                <textarea id="address" class="form-control input-sm" rows="2" readonly></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="customer">โทรศัพท์</label>
                            <div class="col-sm-9">
                                <input class="form-control input-sm" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6">
                    <form class="form-horizontal">
                        
                        
                        <div class="form-group">
                            <label class="control-label col-sm-3">ประเภทลูกค้า</label>
                            <div class="col-sm-7">
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> ลูกค้าทั่วไป
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> บริษัทประกัน
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">เลขที่เคลม</label>
                            <div class="col-sm-7">
                                <input class="form-control input-sm">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="phone">บริษัทประกัน</label>
                            <div class="col-sm-7">
                                <select class="form-control input-sm">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <button class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-save-file"></span> จัดการ</button>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">ความเสียหาย</label>
                            <div class="col-sm-7">
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> น้อย
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> ปานกลาง
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> มาก
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="phone">ตำแหน่งการชน</label>
                            <div class="col-sm-7">
                                <select class="form-control input-sm">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>