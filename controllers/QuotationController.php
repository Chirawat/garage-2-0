<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\DamagePosition;
use app\models\Customer;
use app\models\Quotation;
use app\models\Viecle;
use app\models\Description;
use yii\db\Query;
use yii\Helpers\ArrayHelper;



class QuotationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $quotation = new Quotation();
        $viecle = new Viecle();
        
        if( Yii::$app->request->get('plate_no') ){
            $viecle = Viecle::find()->where(['plate_no' => Yii::$app->request->get('plate_no')])->one();
        }
        
        $viecleList = Viecle::find()->all();
        $damagePostions = DamagePosition::find()->all();
        $insuranceCompanies = Customer::find()->where(['type' => 'INSURANCE_COMP'])->all();
        
        return $this->render('index',[
            'quotation' => $quotation,
            'viecle' => $viecle,
            'viecleList' => $viecleList,
            'insuranceCompanies' => $insuranceCompanies,
            'damagePostions' => $damagePostions,
            //'descriptions' => $descriptions,
        ]);
    }
    
    public function actionSearch(){
        $request = Yii::$app->request;
        
        if($request->post('plate_no')){
            $VID = Viecle::find()->where(['plate_no' => $request->post('plate_no')])->all();
            $quotations = Quotation::find()->where(['VID' => $VID])->all();
            
            return $this->render('search', [
               'quotations'  => $quotations,
            ]);
        }
        
        if( $request->post('quotation_id') ){
            $quotations = Quotation::find()->where(['QID' => $request->post('quotation_id') ])->all();
            
            return $this->render('search', [
               'quotations'  => $quotations,
            ]); 
        }
        
        return $this->render('search');
    }
    
    public function actionQuotationSave(){
       if( Yii::$app->request->isAjax){    
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $request = Yii::$app->request;
            $data = $request->bodyParams;

            // Quation data
            ///////////////////////////////////////////////////////////////////////////////
            $quotation = new Quotation();

           // get customer id from viecle detail
            $VID = $data["quotation_info"]["vieclePlateNo"];
            $viecle = Viecle::findOne($VID);
           
            // Fill up CID / based on customer type
            $customerType = $data["quotation_info"]["customerType"];
            if($customerType == "GENERAL") { // general customer
                // Fill up Quotation's VID
                $quotation->CID = $viecle->owner0['CID'];
            }
           else{ // insurance company
               // get customer id from customer with type 'insurance' / key already represents VID
               $quotation->CID =  $data["quotation_info"]["insuranceCompany"];
               
           }
           
            // Fill up VID
            $quotation->VID = $viecle->VID;

            // Fill up EID
            $quotation->Employee = Yii::$app->user->identity->getId();
           
           
           // quotation id
            //$quotation->quotation_id = $data["quotation_info"]["quotationId"];;

           // quotation date
           $quotation->quotation_date = date("Y-m-d");
           
           // claim no
            $quotation->claim_no = $data["quotation_info"]["claimNo"];
           
           // damage level
           $quotation->damage_level = $data["quotation_info"]["damageLevel"];
           
           //damage position
           $quotation->damage_position = $data["quotation_info"]["damagePosition"];
           
           // Save Model
            if( $quotation->validate() ){
                $ret = $quotation->save();
            }
           else{
               return $quotation->errors;
           }

           // find latest id
           $QID = Quotation::find()->select(['QID'])->orderBy(['QID' => SORT_DESC])->one()["QID"];
           
            // Description data
            ///////////////////////////////////////////////////////////////////////////////

            // Maintenance
            if(!empty($data["maintenance_list"])){
                for($i = 0; $i < sizeOf($data["maintenance_list"]); $i++){
                    $description = new Description();

                    $description->QID = $QID;
                    $description->row = 1;
                    $description->description = $data["maintenance_list"][$i]["list"];
                    $description->type = "MAINTENANCE";
                    $description->price = $data["maintenance_list"][$i]["price"];

                    if( $description->validate() )
                        $ret = $description->save();
                    else
                        return $description->errors;
                }
            }

            // Part
            if(!empty($data["part_list"])){
                for($i = 0; $i < sizeOf($data["part_list"]); $i++){
                    $description = new Description();

                    $description->QID = $QID;
                    $description->row = 1;
                    $description->description = $data["part_list"][$i]["list"];
                    $description->type = "PART";
                    $description->price = $data["part_list"][$i]["price"];

                    if( $description->validate() )
                        $ret = $description->save();
                    else
                        return $description->errors;
                }
            }
            

            if( $ret ){
               return ['status' => 'sucess', 'QID' => $QID];
            }
           else{
               return ['status' => 'failed', 'error' => $ret];
           }
       }
   }
}