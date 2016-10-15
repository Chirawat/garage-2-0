<?php

namespace app\controllers;

use Yii;
use app\models\Viecle;
use app\models\ViecleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ViecleController implements the CRUD actions for Viecle model.
 */
class TestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actionAddViecle(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        for($i = 0; $i < 100; $i++){
            $viecle = new Viecle();
            $viecle->plate_no = "กก " . str_pad($i, 4, '0', STR_PAD_LEFT);
            if($viecle->validate())
                $viecle->save();
            else
                return $viecle->errors;
        }
    }
}