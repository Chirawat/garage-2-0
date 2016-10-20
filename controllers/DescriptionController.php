<?php

namespace app\controllers;

use Yii;
use app\models\Description;
use app\models\DescriptionSearch;
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
    
    public function actionDescriptionList( $term = null ){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_null($term)) {
            $query = new Query;

            $subQuery = Description::find()->select('description')->distinct()->where(['like', 'description', $term]);

            $maxId = Description::find()->select("MAX(did)")->from('description')->where(['in', 'description', $subQuery])->groupBy('description');

            $descriptionList = $query->select(["did AS key", "description AS value", 'price'])->from('description')->where(['in', 'did', $maxId]);

            $row = $descriptionList->all();


            return $row;
        }
            
    }
    
    public function actionUpdate(){
        if( Yii::$app->request->isAjax){    
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $request = Yii::$app->request;
            $data = $request->bodyParams;
            
            // Maintenance
            if(!empty($data["maintenance_list"])){
                for($i = 0; $i < sizeOf($data["maintenance_list"]); $i++){
                    $description = new Description();
            
                    $description->QID = $data["qid"];
                    $description->description = $data["maintenance_list"][$i]["list"];
                    $description->type = "MAINTENANCE";
                    $description->price = $data["maintenance_list"][$i]["price"];
                    
                    /* update 20161019: for history of description */
                    $description->date = date("Y-m-d");

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
                    $description->date = date("Y-m-d");

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
