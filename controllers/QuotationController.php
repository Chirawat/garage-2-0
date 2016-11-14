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
use app\models\Claim;
use yii\db\Query;
use yii\Helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use yii\data\ActiveDataProvider;

class QuotationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index'],
                'rules' => [
                    [
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
        $quotationId = Quotation::find()->where(['YEAR(quotation_date)' => date('Y')])->count() + 1;
        $quotationId = $quotationId . "/" . (date('Y') + 543);
        
        $viecle = new Viecle();
        
        if( Yii::$app->request->get('plate_no') ){
            $viecle = Viecle::find()->where(['plate_no' => Yii::$app->request->get('plate_no')])->one();
        }
        
        // list in combobox
        $viecleList = Viecle::find()->all();
        $damagePostions = DamagePosition::find()->all();
        $insuranceCompanies = Customer::find()->where(['type' => 'INSURANCE_COMP'])->all();
        
        return $this->render('index',[
            'quotation' => $quotation,
            'viecle' => $viecle,
            'viecleList' => $viecleList,
            'insuranceCompanies' => $insuranceCompanies,
            'damagePostions' => $damagePostions,
            'quotationId' => $quotationId,
        ]);
    }
    
    public function actionSearch(){
        $request = Yii::$app->request;
        $dataProvider = new ActiveDataProvider([
                'query' => Quotation::find()->joinWith(['viecle', 'claim', 'customer']),
                'pagination' => [
                'pageSize' => 20,
                ],
            ]);
        
         if($request->isPost){
            $plate_no = $request->post('plate_no');
            $dataProvider = new ActiveDataProvider([
                'query' => Quotation::find()->joinWith(['viecle', 'claim'])->where(['like', 'viecle.plate_no', $plate_no]),
                'pagination' => [
                'pageSize' => 20,
                ],
            ]);
        }

        return $this->render('search', [
            'dataProvider' => $dataProvider,
        ]);
        
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
               $quotation->CID =  $data["quotation_info"]["CID"];
               
           }
           
            // Fill up VID
            $quotation->VID = $viecle->VID;

            // Fill up EID
            $quotation->EID = Yii::$app->user->identity->getId();
           
           
           // quotation id
           $quotationId = Quotation::find()->where(['YEAR(quotation_date)' => date('Y')])->count() + 1;
           $quotationId = $quotationId . "/" . (date('Y') + 543);
           $quotation->quotation_id = $quotationId;

           // quotation date
           $quotation->quotation_date = date("Y-m-d");
           
           // claim no
           $claim = new Claim();
           $claim->claim_no = $data["quotation_info"]["claimNo"];
           $claim->save();

           $claim = Claim::find()->orderBy(['CLID' => SORT_DESC])->one();
           $quotation->CLID = $claim->CLID;
           
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
                    
                    /* update 20161019: for history of description */
                    $description->date = date('Y-m-d H:i:s');

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
                    
                    /* update 20161019: for history of description */
                    $description->date = date('Y-m-d H:i:s');

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
    
    public function actionView($qid){
        $request = Yii::$app->request;
        
        $quotation = Quotation::findOne( $qid );
        $viecle = $quotation->viecle;
        $descriptions = $quotation->descriptions;
        
        // find date
        $dateLists = Description::find()->select(['date'])->distinct()->where(['QID' => $qid])->orderBy(['date' => SORT_DESC])->all();
        $dateIndex = 0;
        if($request->isAjax){
            $dateIndex = $request->post('dateIndex');
        }
        
        // Description
        $query = Description::find()->where(['QID' => $qid, 'type' => 'MAINTENANCE', 'date' => $dateLists[$dateIndex]->date ]);
        $maintenanceDescriptionModel = $query->all();
        $sumMaintenance = $query->sum('price');
        
        $query = Description::find()->where(['QID' => $qid, 'type' => 'PART', 'date' => $dateLists[$dateIndex]->date]);
        $partDescriptionModel = $query->all();
        $sumPart = $query->sum('price');
        
        $numRow = 0;
        if(sizeof($maintenanceDescriptionModel) > sizeof($partDescriptionModel))
            $numRow = sizeof($maintenanceDescriptionModel);
        else
            $numRow = sizeof($partDescriptionModel);

        return $this->render('view', [
            'quotation' => $quotation,
            'viecle' => $viecle,
            'viecleList' => [],
            'insuranceCompanies' => [],
            'damagePostions' => [],
            
            // description
            'descriptions' => $descriptions,
            'maintenanceDescriptionModel' => $maintenanceDescriptionModel,
            'sumMaintenance' => $sumMaintenance,
            'partDescriptionModel' => $partDescriptionModel,
            'sumPart' => $sumPart,
            'numRow' => $numRow,
            
            'dateLists' => $dateLists,
            'dateIndex' => $dateIndex,
            'quotationId' => $quotation->quotation_id,
        ]);
    }
    
    public function actionEdit($qid){
        $quotation = Quotation::findOne( $qid );
        $viecle = $quotation->viecle;
        $descriptions = $quotation->descriptions;
        
         // find date
        $dateLists = Description::find()->select(['date'])->distinct()->where(['qid' => $qid])->orderBy(['date'=>SORT_DESC])->all();
        $dateIndex = 0;
//        if($request->isAjax){
//            $dateIndex = $request->post('dateIndex');
//        }

        // Description
        $query = Description::find()->where(['QID' => $qid, 'type' => 'MAINTENANCE', 'date' => $dateLists[$dateIndex]]);
        $maintenanceDescriptionModel = $query->all();
        $sumMaintenance = $query->sum('price');
        
        $query = Description::find()->where(['QID' => $qid, 'type' => 'PART', 'date' => $dateLists[$dateIndex]]);
        $partDescriptionModel = $query->all();
        $sumPart = $query->sum('price');
        
        $numRow = 0;
        if(sizeof($maintenanceDescriptionModel) > sizeof($partDescriptionModel))
            $numRow = sizeof($maintenanceDescriptionModel);
        else
            $numRow = sizeof($partDescriptionModel);
        
        return $this->render('edit', [
            'quotation' => $quotation,
            'viecle' => $viecle,
            'viecleList' => [],
            'insuranceCompanies' => [],
            'damagePostions' => [],
            
            // description
            'descriptions' => $descriptions,
            'maintenanceDescriptionModel' => $maintenanceDescriptionModel,
            'sumMaintenance' => $sumMaintenance,
            'partDescriptionModel' => $partDescriptionModel,
            'sumPart' => $sumPart,
            'numRow' => $numRow,

            'quotationId' => $quotation->quotation_id,
        ]);
    }
    
    public function actionReport($qid, $dateIndex = null) {
        $request = Yii::$app->request;
        
        $qid = Quotation::findOne($qid);
        
        // Quotation
        $model = Quotation::find()->where(['QID' => $qid])->one();
        
        // Customer
        $customerModel = Customer::find()->where(['CID' => $model->CID])->one();
        
        // Viecle
        $viecleModel = Viecle::find()->where(['VID' => $model->VID])->one();
        
        // find date
        $dateLists = Description::find()->select(['date'])->distinct()->orderBy(['date' => SORT_DESC])->all();
        
        if( $dateIndex == null)
            $dateIndex = 0;
        
        $dt = date_create($dateLists[$dateIndex]->date);
        //if($request->isAjax){
            //$dateIndex = $request->get('dateIndex');
            //$dateIndex = $dateIndex_t;
        //}
        
        // Description
        $query = Description::find()->where(['QID' => $qid, 'type' => 'MAINTENANCE', 'date' => $dateLists[$dateIndex]->date ]);
        $maintenanceDescriptionModel = $query->all();
        $sumMaintenance = $query->sum('price');
        
        $query = Description::find()->where(['QID' => $qid, 'type' => 'PART', 'date' => $dateLists[$dateIndex]->date]);
        $partDescriptionModel = $query->all();
        $sumPart = $query->sum('price');
        
        $numRow = 0;
        if(sizeof($maintenanceDescriptionModel) > sizeof($partDescriptionModel))
            $numRow = sizeof($maintenanceDescriptionModel);
        else
            $numRow = sizeof($partDescriptionModel);
        
        

        //////////////// REPORT PROCEDURE ////////////////////////////////////////
        $content = $this->renderPartial('report',[
            'model' => $model,
            'customerModel' => $customerModel,
            'viecleModel' => $viecleModel,
            
            'maintenanceDescriptionModel' => $maintenanceDescriptionModel,
            'sumMaintenance' => $sumMaintenance,
            'partDescriptionModel' => $partDescriptionModel,
            'sumPart' => $sumPart,
            'numRow' => $numRow,
            
            'dt' => date_format($dt, "Y/m/d") , // 20161027 date in quotation should be description's date
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
        // set to use core fonts only
        'mode' => Pdf::MODE_UTF8, 
        // A4 paper format
        'format' => Pdf::FORMAT_A4, 
        // portrait orientation
        'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
        'destination' => Pdf::DEST_BROWSER, 
        // your html content input
        'content' => $content,  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
        'cssFile' => '@app/web/css/pdf.css',
        // any css to be embedded if required
        //        'cssInline' => '.kv-heading-1{font-size:18px}', 
        // set mPDF properties on the fly
        'options' => ['title' => 'ใบเสนอราคา'],
        // call mPDF methods on the fly
        'methods' => [ 
            //'SetHeader'=>['Krajee Report Header'], 
            'SetFooter'=>['หน้า {PAGENO} / {nb}'],
            ]
        ]);

        $pdf->configure(array(
            'defaultfooterline' => '0', 
            'defaultfooterfontstyle' => 'R',
            'defaultfooterfontsize' => '10',
        ));

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }
}
