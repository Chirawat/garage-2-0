<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Customer;


class ConfigController extends Controller{
    public function actionInsuranceCompany(){
        Yii::$app->formatter->nullDisplay = '-';
        $request = Yii::$app->request;
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
    
    public function actionViecle(){
        return $this->render('viecle',[
            
        ]);
    }
    
    public function actionEmployee(){
        return $this->render('employee',[
            
        ]);
    }
}