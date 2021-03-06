<?php
// rev 20161115-1617
// error ONLY_FULL_GROUP_BY : mysql > SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
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
        Yii::$app->formatter->nullDisplay = '-';
        $dataProvider = new ActiveDataProvider([
                'query' => Quotation::find()->joinWith(['viecle', 'claim', 'customer'])->orderBy(['QID' => SORT_DESC]),
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
        $request = Yii::$app->request;

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if( $request->isPost ){    
            $quotationInfo = $request->post('quotation_info');
                        
            $quotation = new Quotation();    
            $quotation->CID = $quotationInfo['CID'];
            $quotation->VID = $quotationInfo['VID'];

            $quotationId = Quotation::find()->where(['YEAR(quotation_date)' => date('Y')])->count() + 1;
            $quotationId = $quotationId . "/" . (date('Y') + 543);
            $quotation->quotation_id = $quotationId;
            
            $dt = date('Y-m-d H:i:s');
            $claim = Claim::find()->where(['claim_no' => $quotationInfo['claimNo']])->one();
            if($claim == null){
                $claim = new Claim();
                $claim->claim_no = $quotationInfo['claimNo'];
                $claim->VID = $quotation->VID;
                $claim->create_time = $dt;
                $claim->save();
            }
            
            $quotation->CLID = $claim->CLID;
            $quotation->quotation_date = date("Y-m-d");
            $quotation->damage_level = $quotationInfo['damageLevel'];
            $quotation->damage_position = $quotationInfo['damagePosition'];            
            $quotation->EID = Yii::$app->user->identity->getId();         
            
            // update 20170106
            $totalManual = $request->post('totalManual');
            $quotation->maintenance_total = $totalManual['maintenance'];
            $quotation->part_total = $totalManual['part'];
            $quotation->total = $totalManual['total'];

            if($quotation->validate() && $quotation->save()){
                $quotation = Quotation::find()->orderBy(['QID' => SORT_DESC])->one();
                $maintenances = $request->post('maintenance_list');
                if($maintenances != null){
                    foreach($maintenances as $maintenance){
                        $description = new Description();
                        $description->QID = $quotation->QID;
                        $description->description = $maintenance["list"];
                        $description->type = "MAINTENANCE";
                        $description->price = $maintenance["price"];
                        $description->date = $dt;
                        if($description->validate() && $description->save()){
                        }
                        else{
                            return ['status' => false, 'message' => $description->errors];
                        }
                    }
                }
                
                $parts = $request->post('part_list');
                if($parts != null){
                    foreach($parts as $part){
                        $description = new Description();
                        $description->QID = $quotation->QID;
                        $description->description = $part["list"];
                        $description->type = "PART";
                        $description->price = $part["price"];
                        $description->date = $dt;
                        if($description->validate() && $description->save()){
                        }
                        else{
                            return ['status' => false, 'message' => $description->errors];
                        }
                    }
                }
            }
            else{
                return ['status' => false, 'message' => $quotation->errors];
            }
            
            return ['status' => true, 'message' => "บันทึกเสร็จเรียบร้อย", 'QID' => $quotation->QID];
       }
   }
    
    public function actionView($qid){
        $request = Yii::$app->request;
        
        $quotation = Quotation::findOne( $qid );
        $viecle = $quotation->viecle;
        $descriptions = $quotation->descriptions;
        
        // find date
        $revises = Description::find()->select('revise')->distinct()->where(['QID' => $qid])->orderBy(['revise' => SORT_DESC])->all();
        $dateLists = [];
        foreach($revises as $revise_t){
            
            $query = Description::find()->where(['QID' => $qid, 'revise' => $revise_t->revise])->orderBy(['date' => SORT_DESC])->orderBy(['DID' => SORT_DESC]);
            $temp = $query->all();
            $dateLists[] = $temp[0];
        }
        
        $dateIndex = 0;
        if($request->isAjax){
            $dateIndex = $request->post('dateIndex');
        }
        
        // Description
        $query = Description::find()->where(['QID' => $qid, 'type' => 'MAINTENANCE', 'date' => $dateLists[$dateIndex]->date ]);
        $maintenanceDescriptionModel = $query->all();
        $quotation->maintenance_total != null ? $sumMaintenance = $quotation->maintenance_total : $sumMaintenance = $query->sum('price');
        
        $query = Description::find()->where(['QID' => $qid, 'type' => 'PART', 'date' => $dateLists[$dateIndex]->date ]);
        $partDescriptionModel = $query->all();
        $quotation->part_total != null ? $sumPart = $quotation->part_total : $sumPart = $query->sum('price');

        $quotation->total != null ? $grandTotal = $quotation->total : $grandTotal = $sumMaintenance + $sumPart;
        
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
            'grandTotal' => $grandTotal,
            'numRow' => $numRow,
            
            'dateLists' => $dateLists,
            'dateIndex' => $dateIndex,
            'quotationId' => $quotation->quotation_id,
        ]);
    }
    
    public function actionEdit($qid){
        $request = Yii::$app->request;

        $quotation = Quotation::findOne( $qid );
        
        $viecle = $quotation->viecle;
        $descriptions = $quotation->descriptions;
        
         // find date
        $dateLists = Description::find()->select(['date'])->distinct()->where(['QID' => $qid])->orderBy(['date'=>SORT_DESC])->all();
        $dateIndex = 0;
        if($request->isAjax){
            $dateIndex = $request->post('dateIndex');
        }

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
    
    public function actionReport($qid, $dateIndex = 0) {
        $request = Yii::$app->request;
        
        // Quotation
        $model = Quotation::find()->where(['QID' => $qid])->one();
        
        // Customer
        $customerModel = $model->customer;
        
        // Viecle
        $viecleModel = $model->viecle;
        
        // find date
        $revises = Description::find()->select('revise')->distinct()->where(['QID' => $qid])->orderBy(['revise' => SORT_DESC])->all();
        $dateLists = [];
        foreach($revises as $revise_t){
            
            $query = Description::find()->where(['QID' => $qid, 'revise' => $revise_t->revise])->orderBy(['date' => SORT_DESC])->orderBy(['DID' => SORT_DESC]);
            $temp = $query->all();
            $dateLists[] = $temp[0];
        }
        
        $dt = date_create($dateLists[$dateIndex]['date']);

        // Description
        $query = Description::find()->where(['QID' => $qid, 'type' => 'MAINTENANCE', 'date' => $dateLists[$dateIndex]['date'] ]);
        $maintenanceDescriptionModel = $query->all();
        if($model->maintenance_total == null) {
            $sumMaintenance = $query->sum('price');
        }
        else {
            $sumMaintenance = $model->maintenance_total;
        }
        
        $query = Description::find()->where(['QID' => $qid, 'type' => 'PART', 'date' => $dateLists[$dateIndex]['date']]);
        $partDescriptionModel = $query->all();
        if($model->part_total == null) {
            $sumPart = $query->sum('price');
        }
        else {
            $sumPart = $model->part_total;
        }
        
        $total = null;
        $model->total !== null ? $total = $model->total : $total = $sumMaintenance + $sumPart;

        $total = round($total, 2);

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
            'total' => $total,
            'sumPart' => $sumPart,
            'numRow' => $numRow,
            
            'dt' => date_format($dt, "Y/m/d") , // 20161027 date in quotation should be description's date
            
            //'revise' => $dateLists[$dateIndex]->revise,
        ]);
        
        $header = $this->renderPartial('report_header',[
            'model' => $model,
            'dt' => date_format($dt, "Y/m/d") , // 20161027 date in quotation should be description's date
            'customerModel' => $customerModel,
            'viecleModel' => $viecleModel,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = null;
        if( !isset($model->revised) ){
            $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
                
            'marginTop' => 118,
                
            'marginBottom' => 50,
                
            'marginRight' => 7,
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
                'SetHTMLHeader'=>$header,
                'SetFooter'=>['ลงชื่อ&emsp;............................................ ผู้เสนอราคา<br>
                (&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;)&emsp;&emsp;&emsp;&emsp;&emsp;<br>  หน้า {PAGENO} / {nb}'],
                ]
            ]);
        }
        else{
            $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
                
            'marginTop' => 118,
                
            'marginBottom' => 50,
                
            'marginRight' => 7,
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
                'SetHTMLHeader'=>$header, 
                'SetFooter'=>['ลงชื่อ&emsp;............................................ ผู้เสนอราคา<br>
                (&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;)&emsp;&emsp;&emsp;&emsp;&emsp;<br>  REVISED: ' . $dateLists[$dateIndex]->revise . ' หน้า {PAGENO} / {nb}'],
                ]
            ]);
        }
        $pdf->configure(array(
            'defaultfooterline' => '0', 
            'defaultfooterfontstyle' => 'R',
            'defaultfooterfontsize' => '10',
        ));

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }
    
    public function actionRevisedUp($QID=null, $up=false){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $quotation = Quotation::findOne($QID);
        if($up !== "false") {
            if($quotation->revised === null){
                $quotation->revised = 1;
            }
            else{
                $quotation->revised += 1;
            }
        }
        
        // update quotation
        if($quotation->validate() && $quotation->save()){
            // update description
            $dateLists = Description::find()->select(['date'])->distinct()->where(['QID' => $QID])->orderBy(['date' => SORT_DESC])->all();

            // Description
            $query = Description::find()->where(['QID' => $QID, 'type' => 'MAINTENANCE', 'date' => $dateLists[0]->date ]);
            $maintenances = $query->all();
            foreach($maintenances as $maintenance){
                $maintenance_t = Description::findOne($maintenance->DID);
                $maintenance_t->revise = $quotation->revised;
                $maintenance_t->save();
            }

            $query = Description::find()->where(['QID' => $QID, 'type' => 'PART', 'date' => $dateLists[0]->date]);
            $parts = $query->all();
            foreach($parts as $part){
                $part_t = Description::findOne($part->DID);
                $part_t->revise = $quotation->revised;
                $part_t->save();
            }
            return true;
        }
        else{
            return $quotation->errors;
        }
    }
}
