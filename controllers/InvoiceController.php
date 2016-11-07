<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use app\Models\Invoice;
use app\Models\InvoiceDescription;
use app\Models\Viecle;
use app\Models\Quotation;
use app\Models\Customer;
use yii\helpers\Url;
use yii\db\Query;

class InvoiceController extends Controller
{
    function num2thai($number){
        $t1 = array("ศูนย์", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
        $t2 = array("เอ็ด", "ยี่", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน");
        $zerobahtshow = 0; // ในกรณีที่มีแต่จำนวนสตางค์ เช่น 0.25 หรือ .75 จะให้แสดงคำว่า ศูนย์บาท หรือไม่ 0 = ไม่แสดง, 1 = แสดง
        (string) $number;
        $number = explode(".", $number);
        if(!empty($number[1])){
            if(strlen($number[1]) == 1){
                $number[1] .= "0";
            }else if(strlen($number[1]) > 2){
                if($number[1]{2} < 5){
                    $number[1] = substr($number[1], 0, 2);
                }else{
                    $number[1] = $number[1]{0}.($number[1]{1}+1);
                }
            }
        }

        for($i=0; $i<count($number); $i++){
            $countnum[$i] = strlen($number[$i]);
            if($countnum[$i] <= 7){
                $var[$i][] = $number[$i];
            }else{
                $loopround = ceil($countnum[$i]/6);
                for($j=1; $j<=$loopround; $j++){
                    if($j == 1){
                            $slen = 0;
                        $elen = $countnum[$i]-(($loopround-1)*6);
                    }else{
                        $slen = $countnum[$i]-((($loopround+1)-$j)*6);
                        $elen = 6;
                    }
                    $var[$i][] = substr($number[$i], $slen, $elen);
                }
            }

            $nstring[$i] = "";
            for($k=0; $k<count($var[$i]); $k++){
                if($k > 0) $nstring[$i] .= $t2[7];
                    $val = $var[$i][$k];
                    $tnstring = "";
                    $countval = strlen($val);
                for($l=7; $l>=2; $l--){
                    if($countval >= $l){
                        $v = substr($val, -$l, 1);
                        if($v > 0){
                            if($l == 2 && $v == 1){
                                $tnstring .= $t2[($l)];
                            }elseif($l == 2 && $v == 2){
                                $tnstring .= $t2[1].$t2[($l)];
                            }else{
                                $tnstring .= $t1[$v].$t2[($l)];
                            }
                        }
                    }
                }

                if($countval >= 1){
                    $v = substr($val, -1, 1);
                    if($v > 0){
                        if($v == 1 && $countval > 1 && substr($val, -2, 1) > 0){
                            $tnstring .= $t2[0];
                        }else{
                            $tnstring .= $t1[$v];
                        }
                    }
                }

                $nstring[$i] .= $tnstring;
            }
        }
        $rstring = "";
        if(!empty($nstring[0]) || $zerobahtshow == 1 || empty($nstring[1])){
            if($nstring[0] == "") $nstring[0] = $t1[0];
                $rstring .= $nstring[0]."บาท";
        }
        if(count($number) == 1 || empty($nstring[1])){
            $rstring .= "ถ้วน";
        }else{
            $rstring .= $nstring[1]."สตางค์";
        }
        return $rstring;
    }

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
                'only' => ['create'],
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

//        if($request->get('cid'))
//            $customer = Customer::findOne($request->get('cid'));
//        else
//            $customer = new Customer();
//
//        // Create Customer
        $customer_t = new Customer();
//        if( $customer_t->load($request->post()) && $customer_t->validate() ){
//            $customer_t->save();
//
//            // find latest record
//            $customer = Customer::find()->orderBy(['CID' => SORT_DESC])->one();
//            return $this->redirect(['invoice/index', 'cid'=>$customer->CID]);
//        }


        $iid = Invoice::find()->where(['YEAR(date)' => date('Y')])->count();
        $iid = ($iid + 1) . "/" . (date('Y')+543);

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

            'iid' => $iid,
//            'customer' => $customer,
            'customer_t' => $customer_t, //
            'customers' => $customers,

            'viecleList' => $viecleList,
            'insuranceCompanies' => $insuranceCompanies,
            'viecle' => $viecle,
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

        $thbStr = $this->num2thai(1200);
        $content = $this->renderPartial('invoice_report',[
            'invoice' => $invoice,

            'total' => $total,
            'vat' => $vat,
            'grandTotal' => $grandTotal,
            'thbStr' => $this->num2thai($grandTotal),
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
        $dateLists = InvoiceDescription::find()->select(['date'])->distinct()->orderBy(['date'=>SORT_DESC])->all();
        $dateIndex = 0;
        if($request->isAjax){
            $dateIndex = $request->post('dateIndex');
        }

        // Description
        $query = InvoiceDescription::find()->where(['IID' => $iid, 'date' => $dateLists[$dateIndex]->date ]);
        $descriptions = $query->all();

        $total = $invoice->getInvoiceDescriptions()->sum('price');
        $vat = $total * 0.07;
        $grandTotal = $total + $vat;

         $customer = $invoice->customer;

        return $this->render('view', [
            'dateLists' => $dateLists,
            'dateIndex' => $dateIndex,

            'customer' => $customer,
            'invoice' => $invoice,
            'descriptions' => $descriptions,
            'total' => $total,
            'vat' => $vat,
            'grandTotal' => $grandTotal,
        ]);
    }

    public function actionCreate(){
        $request = Yii::$app->request;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // Create invoice
        $invoice = new Invoice();

        $invoice->CID = $request->post('cid');
        $invoice->date = date('Y-m-d H:i:s');
        $invoice->employee = Yii::$app->user->identity->getId();

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
            $ret['message'] = "Error: " + $invoice->errors;
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
        $customer = $invoice->customer;
        $invoiceDescriptions = $invoice->invoiceDescriptions;
        
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

        return $this->render('edit',[
            'customer' => $customer,
            'invoiceDescriptions' => $invoiceDescriptions,
        ]);
    }
    
    public function actionReport($iid, $dateIndex = null){
        // find date
        $dateLists = InvoiceDescription::find()->select(['date'])->distinct()->orderBy(['date' => SORT_DESC])->all();

        if( $dateIndex == null)
            $dateIndex = 0;

        $invoice = Invoice::findOne($iid);

        $query = InvoiceDescription::find()->where(['iid' => $iid, 'date' => $dateLists[$dateIndex]]);
        $descriptions = $query->all();

        $total = $query->sum('price');
        $vat = $total * 0.07;
        $grandTotal = $total + $vat;

        $content = $this->renderPartial('report', [
            'invoice' => $invoice,

            'descriptions' => $descriptions,
            'total' => $total,
            'vat' => $vat,
            'grandTotal' => $grandTotal,
            'thbStr' => $this->num2thai($grandTotal),
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

    public function actionSearch(){
        $request = Yii::$app->request;

        if($request->post()){
            $fullname = $request->post('fullname');
            $customers = Customer::find()->where(['like', 'fullname', $fullname])->all();

            if( sizeof($customers) != 0 ){
                return $this->render('search', [
                    'status' => 'success',
                    'customers' => $customers,
                ]);
            }
            else{
                return $this->render('search', [
                    'status' => 'failed',
                ]);
            }
        }
        if($request->post()){
            $CID = Customer::findOne($CID);
            $invoice = Invoice::find()->where(['CID' => $CID ])->all();

            if( !empty( $invoice ) )
                return $this->render('search', [
                   'invoice'  => $invoice,
                    'status' => 'success',
                ]);

            else
                return $this->render('search', [
                   'invoice'  => $invoice,
                    'status' => 'failed',
                ]);
        }

        return $this->render('search');
    }

    public function actionUpdateDetail( $CID ){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $customer = Customer::findOne($CID);
        return $customer;
    }
}
?>
