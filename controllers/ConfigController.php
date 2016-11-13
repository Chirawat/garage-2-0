<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Customer;
use app\models\ViecleName;
use app\models\ViecleModel;
use app\models\Employee;


class ConfigController extends Controller{
    public function actionInsuranceCompany(){
        $request = Yii::$app->request;
        Yii::$app->formatter->nullDisplay = '-';
        $query = Customer::find()->where(['type' => 'INSURANCE_COMP']);
        $dataProvider  = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $customer = new Customer();
        
        if( $customer->load( $request->post()) ){
            $customer->type = "INSURANCE_COMP";
            $customer->save();
            
            $customer = new Customer(); //  empty
        }
        
        return $this->render('insurance_comp',[
            'dataProvider' => $dataProvider,
            'customer' => $customer,
        ]);
    }
    
    public function actionViewInsurance( $CID ){
        $customer = Customer::findOne( $CID );
        
        return $this->renderAjax('update_insurance_modal', [
            'customer' => $customer,
        ]);
    }
    
    public function actionUpdateInsurance( $CID ){
        $customer = Customer::findOne( $CID );
        
        if( $customer->load(Yii::$app->request->post()) ){
            $customer->save();
            return $this->redirect(['config/insurance-company']);
        }
    }
    
    public function actionViecle(){
        $request = Yii::$app->request;
        Yii::$app->formatter->nullDisplay = '-';
        
        $query = ViecleModel::find();
        $dataProvider  = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $viecleName = ViecleName::find()->all();
        
        $viecleName_t = new ViecleName();
        
        if($viecleName_t->load( $request->post() ) && $viecleName_t->validate()){
            $viecleName_t->save();
            return $this->redirect(['viecle']);
        }
        
        $viecleModel = new ViecleModel();
        
        if($viecleModel->load($request->post()) && $viecleModel->validate()){
            $viecleModel->save();
            return $this->redirect(['viecle']);
        }
        
        return $this->render('viecle',[
            'dataProvider' => $dataProvider,
            'viecleName' => $viecleName,
            'viecleName_t' => $viecleName_t,
            'viecleModel' => $viecleModel,
            
        ]);
    }
    
    public function actionViecleModel($viecleName){
        $query = ViecleModel::find()->where(['viecle_name' => $viecleName]);
        $dataProvider  = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->renderAjax('viecle_model', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionEmployee(){
        $query = Employee::find();
        $request = Yii::$app->request;
        $dataProvider  = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $employee = new Employee();
        
        if( $employee->load($request->post()) && $employee->validate() ){
            $employee->save();
        }
        
        return $this->render('employee',[
            'dataProvider' => $dataProvider,
            'employee' => $employee,
        ]);
    }
    
    public function actionViewEmployee( $EID ){
        $employee = Employee::findOne( $EID );
        
        return $this->renderAjax('modal_update_employee',[
            'employee' => $employee,
        ]);
    }
    public function actionUpdateEmployee($EID){
        $employee = Employee::findOne($EID);
        if($employee->load(Yii::$app->request->post()) && $employee->validate()){
            $employee->save();
            return $this->redirect(['config/employee']);
        }
    }
}