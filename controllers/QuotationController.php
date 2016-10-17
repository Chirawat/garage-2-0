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
        ]);
    }
}