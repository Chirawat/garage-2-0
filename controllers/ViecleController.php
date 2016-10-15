<?php

namespace app\controllers;

use Yii;
use app\models\Viecle;
use app\models\ViecleSearch;
use app\models\ViecleName;
use app\models\ViecleModel;
use app\models\BodyType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ViecleController implements the CRUD actions for Viecle model.
 */
class ViecleController extends Controller
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
     * Lists all Viecle models.
     * @return mixed
     */
    public function actionIndex()
    {
        //$searchModel = new ViecleSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Viecle model.
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
     * Creates a new Viecle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Viecle();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->VID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Viecle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->VID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Viecle model.
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
     * Finds the Viecle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Viecle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Viecle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionDetail( $plate_no ){
        $model = Viecle::find()->where(['plate_no' => $plate_no])->one();
        $viecleName = ViecleName::find()->all();
        $bodyType = BodyType::find()->all();
        $request = Yii::$app->request;
        if( $request->post() ){
            //$model->viecle_name = $request->post('viecle_name')
            
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $request->post();
        }
        
        if( $model != null )
            return $this->render('detail',[
                'model' => $model,
                'viecleName' => $viecleName,
                'bodyType' => $bodyType,
            ]);
        else
            return $this->render('index', ['status' => 'not found']);
    }
    
    public function actionModelList(){
        $request = Yii::$app->request;
//        if( $request->post() ){
            //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $viecleModels = ViecleModel::find()->where(['viecle_name' => $request->post('viecleNameId')])->all(); 
            foreach($viecleModels as $viecleModel){
                echo "<option value='" . $viecleModel['id'] . "'>" . $viecleModel['model'] . "</option>";
            }
//        }
    }
}
