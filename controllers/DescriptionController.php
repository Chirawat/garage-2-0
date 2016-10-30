<?php

namespace app\controllers;

use Yii;
use app\models\Description;
use app\models\DescriptionSearch;
use app\models\Viecle;
use app\models\Quotation;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * DescriptionController implements the CRUD actions for Description model.
 */
class DescriptionController extends Controller
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

    /**
     * Lists all Description models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DescriptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Description model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Description model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Description();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->DID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Description model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->DID]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Deletes an existing Description model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Description model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Description the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Description::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionDescriptionList( $term = null, $vid = null ){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_null($term)) {
            $query = new Query;
            
            // viecle model
            $viecle = Viecle::findOne($vid);
            $viecleModel = $viecle->viecleModel;
            
            // find viecle with the same model
            $viecles = Viecle::find()->where(['viecle_model' => $viecleModel])->all();

            // Make a list of descriptions and prices
            $descriptions = [];
            foreach($viecles as $viecle){
                $quotations = $viecle->quotations;
                foreach($quotations as $quotation){
                    foreach($quotation->getDescriptions()->where(['like', 'description', $term])->orderBy(['DID' => SORT_DESC])->all() as $des){
                        array_push($descriptions, $des);
                    }
                }
            }
            
            $des_price =  ArrayHelper::getColumn($descriptions, function($elements){
                return [
                    'DID' => $elements['DID'], 
                    'description' => $elements['description'], 
                    'price' => $elements['price'] 
                ];
            });
            
            $grouped_des_price = ArrayHelper::index($des_price, null, 'description');
            
            $latestPrice = [];
            foreach($grouped_des_price as $dp){
                array_push($latestPrice, $dp[0]);
            }    
            
            // revised 20161030 - find out description based on viecle model.
            return ArrayHelper::getColumn($latestPrice, function($element){
               return  ['key' => $element['DID'], 'value' =>  $element['description'], 'price' => $element['price']];
            });
        }
            
    }
    
    public function actionUpdate(){
        if( Yii::$app->request->isAjax){    
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $request = Yii::$app->request;
            $data = $request->bodyParams;
            
            $dt = date('Y-m-d H:i:s');
            
            // Maintenance
            if(!empty($data["maintenance_list"])){
                for($i = 0; $i < sizeOf($data["maintenance_list"]); $i++){
                    $description = new Description();
            
                    $description->QID = $data["qid"];
                    $description->description = $data["maintenance_list"][$i]["list"];
                    $description->type = "MAINTENANCE";
                    $description->price = $data["maintenance_list"][$i]["price"];
                    
                    /* update 20161019: for history of description */
                    $description->date = $dt;

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

                    $description->QID = $data["qid"];
                    $description->description = $data["part_list"][$i]["list"];
                    $description->type = "PART";
                    $description->price = $data["part_list"][$i]["price"];
                    
                    /* update 20161019: for history of description */
                    $description->date = $dt;

                    if( $description->validate() )
                        $ret = $description->save();
                    else
                        return $description->errors;
                }
            }
            if( $ret ){
               return ['status' => 'sucess', 'QID' => $data["qid"]];
            }
           else{
               return ['status' => 'failed', 'error' => $ret];
           }
        }
    }
    
    public function actionDescHistory($date){
        //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $descriptions = Description::find()->where(['date' => $date])->all();
        
        echo 'console.log("test")';
        
        //return $descriptions;
    }
    
    public function actionAutoGenerated($vid = null, $damageLevel = null, $damagePos = null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $viecleModel = Viecle::findOne( $vid )['viecle_model'];
        $viecles = Viecle::find()->where(['viecle_model' => $viecleModel])->all();
        
        // Make a list of descriptions and prices
        $descriptions = [];
        foreach($viecles as $viecle){
            $query = Quotation::find()->select('QID')->where(['damage_level' => $damageLevel, 'damage_position' => $damagePos ]);
            $query->andWhere(['in', 'VID', $viecle->VID]);
            $quotations = $query->all();
            
            // count description*********** Future 
            $descriptions_t = (new Query())->select(['description, COUNT(description) AS cnt'])->from('description')->where(['in', 'QID', $quotations])->groupBy('description')->all();
            
            foreach($quotations as $quotation){
                foreach($quotation->getDescriptions()->orderBy(['DID' => SORT_DESC])->all() as $des){
                    array_push($descriptions, $des);
                }
            }
        }
        
        //return $descriptions;
        
        $des_price =  ArrayHelper::getColumn($descriptions, function($elements){
            return [
                'DID' => $elements['DID'], 
                'description' => $elements['description'], 
                'price' => $elements['price'],
                'type' => $elements['type'] 
            ];
        });
            
        $grouped_des_price = ArrayHelper::index( $des_price, null, 'description' );

        $latestPrice = [];
        foreach($grouped_des_price as $dp){
            array_push($latestPrice, $dp[0]);
        }    

        $lists =  ArrayHelper::getColumn($latestPrice, function($element){
           return  ['DID' => $element['DID'], 'description' =>  $element['description'], 'price' => $element['price'], 'type' => $element['type']];
        });
        
        // group by type
        return ArrayHelper::index( $lists, 'DID', 'type');
    }
}
