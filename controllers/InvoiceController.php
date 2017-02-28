<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use app\Models\Invoice;
use app\Models\InvoiceDescription;
use app\Models\Reciept;
use app\Models\Viecle;
use app\Models\Quotation;
use app\Models\Customer;
use app\Models\Claim;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\ActiveDataProvider;

use app\controllers\Common;

class InvoiceController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['create', 'index'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex(){
        $request = Yii::$app->request;

        $invoice = new Invoice();

        if( $request->isAjax ){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $invoice = new Invoice();

            $fullname = Yii::$app->request->post('customer');
            $invoice->CID = Customer::find()->where(['fullname' => $fullname])->one()['CID'];
            if( $invoice->CID == "") // error case
                return "invoice->CID cannot blank";

            $invoice->date = date('Y-m-d');
            $invoice->invoice_id = Yii::$app->request->post('invoice_id');
            
            $invoice->employee = Yii::$app->user->identity->getId();

            if( $invoice->validate() ){
                $ret = $invoice->save();

                $IID = Invoice::find()->orderBy(['IID' => SORT_DESC])->one()['IID'];

                $InvoiceDescriptions = Yii::$app->request->post('invoice_description');
                foreach( $InvoiceDescriptions as $InvoiceDescription)   {
                    $InvoiceDescription_t = new InvoiceDescription();
                    $InvoiceDescription_t->IID = $IID;
                    $InvoiceDescription_t->description = $InvoiceDescription['list'];
                    $InvoiceDescription_t->price = $InvoiceDescription['price'];

                    if( $InvoiceDescription_t->validate() ){
                        $ret = $InvoiceDescription_t->save();
                    }
                    else{
                        $ret = $InvoiceDescription_t->errors;
                    }
                }
            }
            else{

                $ret = $invoice->errors;
            }
            //return $ret;
            return $this->redirect(['invoice/view', 'iid' => $IID]);
        }

        $customer_t = new Customer();

        $type = $request->get('type');
        $invoiceId = $this->getInvoiceNumber($type);
        $receiptId = $this->getReceiptNumber($type);
        
        $book_number = date('m') . "/" . (( date('Y') + 543 ) - 2500);

        // list in combobox
        $viecleList = Viecle::find()->all();
        $insuranceCompanies = Customer::find()->where(['type' => 'INSURANCE_COMP'])->all();

        $viecle = new Viecle();

        if( Yii::$app->request->get('plate_no') ){
            $viecle = Viecle::find()->where(['plate_no' => Yii::$app->request->get('plate_no')])->one();
        }

        $customers = Customer::find()->where(['type' => 'GENERAL'])->all();
        $customers = ['fullname' => "เลือกลูกค้า"];

        return $this->render('index',[
            'invoice' => $invoice,

            'invoiceId' => $invoiceId,
            'receiptId' => $receiptId,
            
            'customer_t' => $customer_t, //
            'customers' => $customers,

            'viecleList' => $viecleList,
            'insuranceCompanies' => $insuranceCompanies,
            'viecle' => $viecle,
            
            'book_number' =>$book_number,
        ]);
    }

    public function actionInvoiceReport($iid=null, $invoice_id=null){
        //////////////// REPORT PROCEDURE ////////////////////////////////////////

        if( $invoice_id != null ){
            $invoice = Invoice::find()->where(['invoice_id' => $invoice_id])->one();
        }
        else{
            $invoice = Invoice::find()->where(['iid' => $iid])->one();
        }
        $total = $invoice->getInvoiceDescriptions()->sum('price');
        $vat = $total * 0.07;
        $grandTotal = $total + $vat;

        $thbStr = Common::num2thai(1200);
        $content = $this->renderPartial('invoice_report',[
            'invoice' => $invoice,

            'total' => $total,
            'vat' => $vat,
            'grandTotal' => $grandTotal,
            'thbStr' => Common::num2thai($grandTotal),
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
        'options' => ['title' => 'ใบเสร็จรับเงิน/ใบกํากับภาษี'],
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

    public function actionView($iid=null, $invoice_id=null){
        $request = Yii::$app->request;

        if( $invoice_id != null ){
            $invoice = Invoice::find()->where(['invoice_id' => $invoice_id])->one();
        }
        else{
            $invoice = Invoice::find()->where(['iid' => $iid])->one();
        }

        // find date
        $dateLists = InvoiceDescription::find()->select(['date'])->distinct()->where(['iid' => $iid])->orderBy(['date'=>SORT_DESC])->all();
        $dateIndex = 0;
        if($request->isAjax){
            $dateIndex = $request->post('dateIndex');
        }

        // Description
        $query = InvoiceDescription::find()->where(['IID' => $iid, 'date' => $dateLists[$dateIndex]->date ]);
        $descriptions = $query->all();
        $invoice->total != null ? $total = $invoice->total : $total = $query->sum('price');
        $invoice->total_vat != null ? $vat = $invoice->total_vat : $vat = $total * 0.07;
        $invoice->grand_total != null ? $grandTotal = $invoice->grand_total : $grandTotal = $total + $vat;

        $customer = $invoice->customer;

        $viecle = $invoice->viecle;

        $invoiceId = Invoice::find()->where(['YEAR(date)' => date('Y')])->count();
        $invoiceId = $invoiceId . "/" . (date('Y')+543);

        return $this->render('view', [
            'dateLists' => $dateLists,
            'dateIndex' => $dateIndex,
            'customer' => $customer,
            'viecle' => $viecle,
            'invoice' => $invoice,
            'descriptions' => $descriptions,
            'total' => $total,
            'vat' => $vat,
            'grandTotal' => $grandTotal,

            'invoiceId' => $invoiceId,
        ]);
    }

    private function getInvoiceNumber($type = null) {
        if ($type === null)
            $number = Invoice::find()->where(['type' => null,'YEAR(date)' => date('Y'), 'MONTH(date)' => date('m')])->count() + 1; 
        else
            $number = Invoice::find()->where(['type' => 'General', 'YEAR(date)' => date('Y'), 'MONTH(date)' => date('m')])->count() + 1; 

        $number = str_pad($number, 4, "0", STR_PAD_LEFT);
        $invoiceId = $number . "/" . (( date('Y') + 543 ) - 2500);

        if ($type === null)
            return "IV-" . $invoiceId;
        else
            return $invoiceId;
    }

    private function getReceiptNumber($type = null) {
        if($type === 'General') {
            $number = Reciept::find()
                        ->joinWith('invoice')
                        ->where(['YEAR(reciept.date)' => date('Y'), 'MONTH(reciept.date)' => date('m'), 'invoice.type' => 'General'])
                        ->count() + 1;
        }
        else {
             $number = Reciept::find()
                        ->joinWith('invoice')
                        ->where(['YEAR(reciept.date)' => date('Y'), 'MONTH(reciept.date)' => date('m'), 'invoice.type' => null])
                        ->count() + 1;
        }
        $number = str_pad($number, 4, "0", STR_PAD_LEFT);
        $receiptId = $number . "/" . (( date('Y') + 543 ) - 2500);
        
        if ($type === 'General') 
            return $receiptId;
        else
            return "RE-" . $receiptId;
    }

    public function actionCreate(){
        $request = Yii::$app->request;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $invoice = new Invoice();

        $type = $request->post('type');
        $invoice->invoice_id = $this->getInvoiceNumber( $type );

        $invoice->book_number = date('m') . '/' . ( (date('Y') + 543) - 2500 );
        $invoice->CID = $request->post('CID');
        $invoice->VID = $request->post('VID');
        $invoice->EID = Yii::$app->user->identity->getId();
        if($type !== 'undefinded')
            $invoice->type = $type;

        $claim_no = $request->post('claim_no');       
        $claim = Claim::find()->where(['claim_no' => $claim_no])->one();
        if($claim == null){ // if not found, create instance of Claim
            $claim = new Claim();
            $claim->claim_no = $claim_no;
            $claim->VID = $invoice->VID;
            $claim->save();

            // retrieve last record
            $claim = Claim::find()->orderBy(['CLID' => SORT_DESC])->one(); 
        }
        $invoice->CLID = $claim->CLID;

        $dt = date('Y-m-d H:i:s');
        $invoice->date = $dt;

        // update 20170106
        $totalManual = $request->post('totalManual');
        $invoice->total = $totalManual['total'];
        $invoice->total_vat = $totalManual['total_tax'];
        $invoice->grand_total = $totalManual['grandTotal'];

        $ret = [];
        if($invoice->validate() && $invoice->save()){
            $invoice = Invoice::find()->orderBy(['IID' => SORT_DESC])->one(); // get latest record

            /* Create descriptions*/
            $invoiceDescription_t = $request->post('invoice');
            foreach($invoiceDescription_t as $description){
                $invoiceDescription = new InvoiceDescription();

                $invoiceDescription->IID = $invoice->IID;
                $invoiceDescription->description = $description['list'];
                $invoiceDescription->price = $description['price'];
                $invoiceDescription->date = $dt;

                if($invoiceDescription->validate()){
                    $invoiceDescription->save();
                    $ret['status'] = true;
                    $ret['IID'] = $invoice->IID;
                    $ret['message'] = "Create invoice successful";
                }
                else{
                    $ret['status'] = false;
                    $ret['message'] = "Error: " + $invoiceDescription->errors;
                }
            }
        }
        else{
            //error
            $ret['status'] = false;
            $ret['message'] = $invoice->errors;
        }

        return $ret;
    }

    public function actionCustomerSearch($type = null, $fullname = null){

        // type
        if($type == "1"){
            $query = Customer::find()->where(['type' => 'GENERAL']);
        }
        else{
            $query = Customer::find()->where(['type' => 'INSURANCE_COMP']);
        }

        // customer name - search agrument
        if($fullname != null){
            $query->andWhere(['like', 'fullname', $fullname]);
        }

        $customers = $query->all();

        if(sizeof($customers) == 0){
            echo '<br><div class="alert alert-danger" role="alert">ไม่พบข้อมูล</div>';

        }
        else{
            echo '<table class="table">
                    <caption>รายชื่อลูกค้า</caption>
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th width="20%">ชื่อลูกค้า</th>
                            <th width="40%">ที่อยู่</th>
                            <th width="20%">เบอร์โทร</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
            $num = 1;
            foreach($customers as $customer){
                echo '<tr>
                        <th scope="row">' . $num++ . '</th>
                        <td>' . $customer->fullname . '</td>
                        <td>' . $customer->address . '</td>
                        <td>' . $customer->phone . '</td>
                        <td><a href="' . Url::to(['invoice/index', 'cid' => $customer->CID ]).'" class="btn btn-default btn-sm">เลือก</a></td>
                    </tr>';
            }

            echo '</tbody></table>';
        }
    }
    
    public function actionEdit($iid){
        $request = Yii::$app->request;

        $invoice = Invoice::findOne( $iid );
        // update 20170106
        $totalManual = $request->post('totalManual');
        $invoice->total = $totalManual['total'];
        $invoice->total_vat = $totalManual['total_tax'];
        $invoice->grand_total = $totalManual['grandTotal'];

        $invoice->save();

        $viecle = $invoice->viecle;

        $customer = $invoice->customer;
        
        if($request->post()){
            /* Create descriptions*/
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $invoiceDescription_t = $request->post('invoice');

            $dt = date('Y-m-d H:i:s');

            foreach($invoiceDescription_t as $description){
                $invoiceDescription = new InvoiceDescription();

                $invoiceDescription->IID = $iid;
                $invoiceDescription->description = $description['list'];
                $invoiceDescription->price = $description['price'];
                $invoiceDescription->date = $dt;

                if($invoiceDescription->validate()){
                    $invoiceDescription->save();
                    $ret['status'] = true;
                    $ret['IID'] = $invoice->IID;
                    $ret['message'] = "Create invoice successful";
                }
                else{
                    $ret['status'] = false;
                    $ret['message'] = "Error: " + $invoiceDescription->errors;
                }

            }
            return $ret;
        }

        // find date
        $dateLists = InvoiceDescription::find()->select(['date'])->distinct()->where(['iid' => $iid])->orderBy(['date'=>SORT_DESC])->all();
        $dateIndex = 0;
        if($request->isAjax){
            $dateIndex = $request->post('dateIndex');
        }

        // Description
        $query = InvoiceDescription::find()->where(['IID' => $iid, 'date' => $dateLists[$dateIndex]->date ]);
        $invoiceDescriptions = $query->all();

        $invoiceId = Invoice::find()->where(['YEAR(date)' => date('Y')])->count();
        $invoiceId = $invoiceId . "/" . (date('Y')+543);

        return $this->render('edit',[
            'invoice' => $invoice,
            'customer' => $customer,
            'viecle' => $viecle,
            'invoiceDescriptions' => $invoiceDescriptions,
            'invoiceId' => $invoiceId,
        ]);
    }
    
    public function actionReport($iid, $dateIndex = null){
        // find date
        $dateLists = InvoiceDescription::find()->select(['date'])->distinct()->where(['IID' => $iid])->orderBy(['date' => SORT_DESC])->all();

        if( $dateIndex == null)
            $dateIndex = 0;

        $invoice = Invoice::findOne($iid);

        $query = InvoiceDescription::find()->where(['iid' => $iid, 'date' => $dateLists[$dateIndex]]);
        $descriptions = $query->all();

        $invoice->total != null ? $total = $invoice->total : $total = $query->sum('price');
        $invoice->total_vat != null ? $vat = $invoice->total_vat : $vat = $total * 0.07;
        $invoice->grand_total != null ? $grandTotal = $invoice->grand_total : $grandTotal = $total + $vat;

        $thb = round(\Yii::$app->request->get('type') !== 'General' ? $grandTotal : $total, 2)
        $content = $this->renderPartial('report', [
            'invoice' => $invoice,

            'descriptions' => $descriptions,
            'total' => $total,
            'vat' => $vat,
            'grandTotal' => $grandTotal,
            'thbStr' => Common::num2thai($thb),
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
        'options' => ['title' => 'ใบแจ้งหนี้'],
        // call mPDF methods on the fly
        'methods' => [
            //'SetHeader'=>['Krajee Report Header'],
            //'SetFooter'=>['หน้า {PAGENO} / {nb}'],      // remove 20161117
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

    public function actionSearch(){
        $request = Yii::$app->request;
        Yii::$app->formatter->nullDisplay = '-';
        $dataProvider = new ActiveDataProvider([
                'query' => Invoice::find()->joinWith(['viecle', 'claim', 'customer'])->where(['not', ['invoice.VID' => null]])->orderBy(['IID' => SORT_DESC]),
                'pagination' => [
                'pageSize' => 20,
                ],
            ]);
        if($request->isPost){
            $plate_no = $request->post('plate_no');
            $dataProvider = new ActiveDataProvider([
                'query' => Invoice::find()->joinWith(['viecle', 'claim'])->where(['like', 'viecle.plate_no', $plate_no]),
                'pagination' => [
                'pageSize' => 20,
                ],
            ]);
        }

        return $this->render('search', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateDetail( $CID ){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $customer = Customer::findOne($CID);
        return $customer;
    }
}
?>
