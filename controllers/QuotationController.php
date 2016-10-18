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
}