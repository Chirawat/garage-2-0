<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Claim;

class ClaimController extends Controller
{
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $claim = new Claim();
        if( $claim->load( $request->post() ) && $claim->validate() ){
            $claim->save();

            $claim = Claim::find()->orderBy(['CLID' => SORT_DESC])->one();
            return $this->redirect(['photo/index', 'CLID' => $claim->CLID]);
        }
//        return $this->render('create', [
//            'claim' => $claim,
//        ]);
    }

}
