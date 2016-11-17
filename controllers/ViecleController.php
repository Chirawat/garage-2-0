<?php

namespace app\controllers;

use Yii;
use app\models\Viecle;
use app\models\ViecleSearch;
use app\models\ViecleName;
use app\models\ViecleModel;
use app\models\Claim;
use app\models\BodyType;
use app\models\Customer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

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
        Yii::$app->formatter->nullDisplay = '-';
        $query = Viecle::find()->with(['owner0', 'viecleName', 'viecleModel'])->orderBy(['VID'=>SORT_DESC]);
        $dataProvider  = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        
        $viecle = new Viecle();
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'viecle' => $viecle,
        ]);
    }

    /**
     * Displays a single Viecle model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($VID)
    {
        
    }

    /**
     * Creates a new Viecle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        
        $model = new Viecle();
        $customer = new Customer();
        
        $viecleName = ViecleName::find()->all();
        $viecleModels = ViecleModel::find()->where(['viecle_name' => $viecleName[0]->id])->all();
        
        if($request->isPost){ 
            // customer
            if( $customer->load($request->post()) && $customer->validate() ){
                $customer->type = "GENERAL";
                $customer->save();
            }
            else{
                var_dump($customer->errors);
                die();
            }
            // viecle
            if ($model->load($request->post()) && $model->validate()) {
                $lastCustomer = Customer::find()->orderBy(['CID' => SORT_DESC])->one(); 
                $model->owner = $lastCustomer->CID;
                $model->save();
            } 
            else {
                var_dump($model->errors);
                die();
            }
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model, // viecle
            'customer' => $customer,
            'viecleModels' => $viecleModels,
            'viecleName' => $viecleName,
        ]);
    }

    /**
     * Updates an existing Viecle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate( $VID )
    {
        $request = Yii::$app->request;
        $model = Viecle::findOne($VID);
        $customer = $model->owner0;
        
        if($request->isPost){ 
            // customer
            if( $customer->load($request->post()) && $customer->validate() ){
                $customer->type = "GENERAL";
                $customer->save();
            }
            else{
                var_dump($customer->errors);
                die();
            }
            // viecle
            if ($model->load($request->post()) && $model->validate()) {
                $lastCustomer = Customer::find()->orderBy(['CID' => SORT_DESC])->one(); 
                $model->owner = $lastCustomer->CID;
                $model->save();
            } 
            else {
                var_dump($model->errors);
                die();
            }
            return $this->redirect(['index']);
        }
        
        $viecleName = ViecleName::find()->all();
        $viecleModels = ViecleModel::find()->where(['viecle_name' => $model->viecle_name])->all();
        
        return $this->render('update', [
            'model' => $model,
            'customer' => $customer,
            'viecleName' =>$viecleName,
            'viecleModels' => $viecleModels,
        ]);
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
    
    public function actionDetail( $plate_no=null ){
        $model = Viecle::find()->where(['plate_no' => $plate_no])->one();

        if( $model == null || $plate_no == null)
            // not found
            return $this->redirect(['viecle/index', 'status' => 'failed']);

        $customer = $model->getOwner0()->one();
        $viecleModels = $model->viecleName->viecleModels;
        
        // update detail
        $request = Yii::$app->request;
        if( $request->post() ){
            $model->load( $request->post() );
            if( $model->validate() )
                $model->save();
            else
                return $model->errors;
            
            $customer->load( $request->post() );
            if( $customer->validate() )
                $customer->save();
            else
                return $customer->errors;
        }
        
        
        $bodyType = BodyType::find()->all();
        $viecleName = ViecleName::find()->all();
        
        
        if( $model != null )
            return $this->render('detail',[
                'model' => $model,
                'customer' => $customer,
                'viecleName' => $viecleName,
                'viecleModels' => $viecleModels,
                'bodyType' => $bodyType,
            ]);
        //else
            //return $this->render('index', ['status' => 'not found']);
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
    
    public function actionViecleDetail($VID){
        $request = Yii::$app->request;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $viecle = Viecle::findOne( $VID );
        
        $ret = [];
        
        // viecle
        $ret['viecle_name'] = $viecle->viecleName['name'];
        $ret['viecle_model'] = $viecle->viecleModel['model'];
        $ret['year'] = $viecle->viecle_year;
        $ret['engine_code'] = $viecle->engin_code;
        $ret['body_code'] = $viecle->body_code;
        
        //customer
        $ret['fullname'] = $viecle->owner0['fullname'];
        $ret['address'] = $viecle->owner0['address'];
        $ret['phone'] = $viecle->owner0['phone'];
        
        return $ret;
    }
    
    public function actionClaim($VID=null, $term=null){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = Claim::find()->where(['VID' => $VID]);
        $query->andWhere(['like', 'claim_no', $term]);
        $claims = $query->all();
        
        return ArrayHelper::getColumn($claims, 'claim_no');
    }
}
