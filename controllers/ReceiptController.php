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
use app\Models\Reciept;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\models\Claim;
use app\models\PaymentStatus;

use app\controllers\Common;

class ReceiptController extends Controller{
    
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
                'only' => ['create','summary', 'dept'],
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

    public function actionReport($iid = null, $dateIndex = null){
        // find date
        $request = Yii::$app->request;
        
        $dateLists = InvoiceDescription::find()->select(['date'])->distinct()->where(['IID' => $iid])->orderBy(['date' => SORT_DESC])->all();

        if( $dateIndex == null)
            $dateIndex = 0;

        $invoice = Invoice::findOne($iid);

        $query = InvoiceDescription::find()->where(['iid' => $iid, 'date' => $dateLists[$dateIndex]]);
        $descriptions = $query->all();

        // update 20170107
        $invoice->total != null ? $total = $invoice->total : $total = $query->sum('price');
        $invoice->total_vat != null ? $vat = $invoice->total_vat : $vat = $total * 0.07;
        $invoice->grand_total != null ? $grandTotal = $invoice->grand_total : $grandTotal = $total + $vat;

        /* Update Reciept Info */
        $reciept = Reciept::find()->where(['IID' => $iid])->one();

        if( count($reciept) == null ){
            $reciept = new Reciept();
            
            $type = $request->get('type');
            $reciept->reciept_id = $this->getReceiptNumber($type);
            $reciept->IID = $invoice->IID;
            $reciept->book_number = date('m') . '/' . ((date('Y') + 543) - 2500);

            $reciept->total = $grandTotal;
            $reciept->date = date('Y-m-d H:i:s');
            $reciept->EID = Yii::$app->user->identity->getId();

            if($reciept->validate() && $reciept->save()){
                // success
                $reciept = Reciept::find()->orderBy(['RID' => SORT_DESC])->one();
            }
            else{
                // failed
                var_dump( $reciept->errors );
                die();
            }    

        }
        
        // update payment status
        $paymentStatus = new PaymentStatus();
        $paymentStatus->RID = $reciept->RID;
        $paymentStatus->CLID = $reciept->invoice['CLID'];
        $paymentStatus->save();
        
        $content = $this->renderPartial('report', [
            'invoice' => $invoice,

            'descriptions' => $descriptions,
            'total' => $total,
            'vat' => $vat,
            'grandTotal' => $grandTotal,
            'thbStr' => Common::num2thai(\Yii::$app->request->get('type') !== 'General' ? $grandTotal : $total),
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
            // 'SetFooter'=>['หน้า {PAGENO} / {nb}'],    //remove 20161117
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

    public function actionSummaryReport($startDate = null, $endDate = null){


        $receipts = Reciept::find()->where(['between', 'UNIX_TIMESTAMP(date)', strtotime($startDate), strtotime($endDate)])->all();

        $mY_t = 0;
        foreach($receipts as $key => $receipt){
            $mY = date("m-Y", strtotime($receipt->date) ); // key
            if($mY != $mY_t){
               $mY_t = $mY;
               $month[$mY_t]= [];
            }
           array_push( $month[$mY_t], $key );
        }


        $content = $this->renderPartial('summary_report', [
            'receipts' => $receipts,
            'month' => $month,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
        // set to use core fonts only
        'mode' => Pdf::MODE_UTF8,
        // A4 paper format
        'format' => Pdf::FORMAT_A4,
        // portrait orientation
        'orientation' => Pdf::ORIENT_LANDSCAPE,
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

    public function actionSummary($startDate=null, $endDate=null){
        $request = Yii::$app->request;

        // query date (difference date)
        $receiptDates = (new Query)->select(["DATE_FORMAT(date, '%m-%Y') AS dt"])->from('reciept')->distinct()->all();
        $receiptDates = ArrayHelper::getColumn($receiptDates, 'dt');

        // post request/ search by condition
        $receipts = null;
        $month = [];
        if($request->post()){
            $startDate =  date_create( "01-" . $receiptDates[ $request->post('start-date') ] );
            $startDate = date_format($startDate, "Y-m-d");

            $endDate = date_create( "01-" . $receiptDates[ $request->post('end-date') ] );
            date_modify($endDate, 'last day of this month');
            $endDate = date_format($endDate, "Y-m-d");

            $receipts = Reciept::find()->where(['between', 'UNIX_TIMESTAMP(date)', strtotime($startDate), strtotime($endDate)])->all();

            $mY_t = 0;
            foreach($receipts as $key => $receipt){
                $mY = date("m-Y", strtotime($receipt->date) ); // key
                if($mY != $mY_t){
                   $mY_t = $mY;
                   $month[$mY_t]= [];
                }
               array_push( $month[$mY_t], $key );
            }
        }

        return $this->render('summary', [
            'receiptDate' => $receiptDates,
            'receipts' => $receipts,
            'month' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function actionDept($startDate=null, $endDate=null){
        Yii::$app->formatter->nullDisplay = '-';
        $request = Yii::$app->request;

        // query date (difference date)
        $invocieDates = (new Query)->select(["DATE_FORMAT(date, '%m-%Y') AS dt"])->from('invoice')->distinct()->all();
        $invoiceDates = ArrayHelper::getColumn($invocieDates, 'dt');

        $month = [];
        $dataProvider = null;
        if( $request->post() ){
            $startDate =  date_create( "01-" . $invoiceDates[ $request->post('start-date') ] );
            $startDate = date_format($startDate, "Y-m-d");

            $endDate = date_create( "01-" . $invoiceDates[ $request->post('end-date') ] );
            date_modify($endDate, 'last day of this month');
            $endDate = date_format($endDate, "Y-m-d");

            $dataProvider = new ActiveDataProvider([
                'query' => Invoice::find()->with('reciept')->where(['between', 'UNIX_TIMESTAMP(date)', strtotime($startDate), strtotime($endDate)])->orderBy(['IID' => SORT_DESC]),
                'pagination' => [
                'pageSize' => 20,
                ],
            ]);
        }
        else{
            $dataProvider = new ActiveDataProvider([
                'query' => Claim::find()->with('paymentStatus')->where(['not', ['create_time' => null]])->orderBy(['CLID' => SORT_DESC]),
                'pagination' => [
                'pageSize' => 25,
                ],
            ]);
        }


        return $this->render('dept', [
            'dataProvider' => $dataProvider,
            'invoiceDates' => $invoiceDates,
            'month' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function actionDeptReport(){
        var_dump( Yii::$app->request->get() );
    }
    
    public function actionMultipleClaim(){
        $invoice = new Invoice();
        $book_number = date('m') . "/" . (( date('Y') + 543 ) - 2500);
        //$number = Reciept::find()->where(['YEAR(date)' => date('Y'), 'MONTH(date)' => date('m')])->count();
        $receiptId = $this->getReceiptNumber();
        
        $paymentStatus = PaymentStatus::find()->all();
        $paymentStatusCLID = ArrayHelper::getColumn($paymentStatus, 'CLID');
        $claim = Claim::find()->where(['not in', 'CLID', $paymentStatusCLID])->all();
        
        $insuranceCompany = Customer::find()->where(['type' => 'INSURANCE_COMP'])->all();
        
        return $this->render('multiple_claim',[
            'book_number' => $book_number,
            'receiptId' => $receiptId,
            'invoice' => $invoice,
            'claim' => $claim,
            'insuranceCompany' => $insuranceCompany,
        ]);
    }
    
    public function actionCreateMultiple(){
        $IID = null;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        
        // create invoice each claim
        $claims = $request->post('claims');
        $descriptions = $request->post('invoice');
        $CID = $request->post('CID');
        $dt = date('Y-m-d H:i:s');
        
        // invoice
        $invoice = new Invoice();
        $invoice->CID = $CID;
        $invoice->book_number = date('m') . '/' . ( (date('Y') + 543) - 2500 );
        $invoice->invoice_id = $this->getInvoiceNumber();
        $invoice->date = $dt;
        $invoice->EID = Yii::$app->user->identity->getId();

        if($invoice->validate() && $invoice->save()){
            $IID = Invoice::find()->orderBy(['IID' => SORT_DESC])->one()['IID'];
            // description
            foreach($descriptions as $description){
                $invoiceDescription = new InvoiceDescription();
                $invoiceDescription->IID = $IID;
                $invoiceDescription->description = $description['list'];
                $invoiceDescription->price = $description['price'];
                $invoiceDescription->date = $dt;

                if($invoiceDescription->validate() && $invoiceDescription->save()){
                }
                else{
                    return $invoiceDescription->errors;
                }
            }    
        }
        else{
            return $invoice->errors;
        }
        
        $receipt = new Reciept();
        $receipt->IID = $IID;
        
        $number = Reciept::find()->where(['YEAR(date)' => date('Y'), 'MONTH(date)' => date('m')])->count() + 1;
        $number = str_pad($number, 4, "0", STR_PAD_LEFT);
        //$receiptId = $number . "/" . (( date('Y') + 543 ) - 2500);
        
        $receipt->reciept_id = $this->getReceiptNumber();
        
        $receipt->total = InvoiceDescription::find()->where(['IID' => $IID])->sum('price');
        
        $receipt->IID = $invoice->IID;
        $receipt->book_number = date('m') . '/' . ((date('Y') + 543) - 2500);
        
        $receipt->date = $dt;
        $receipt->EID = Yii::$app->user->identity->getId();
        
        if($receipt->validate() && $receipt->save()){
            //return true;    
            $receipt = Reciept::find()->orderBy(['RID' => SORT_DESC])->one();
        }
        else{
            return $receipt->errors;
        }
        
        if(isset($claims)){
            foreach($claims as $CLID){
                $paymentStatus = new PaymentStatus();
                $paymentStatus->RID = $receipt->RID;
                $paymentStatus->CLID = $CLID;

                if($paymentStatus->validate() && $paymentStatus->save()){

                }
                else{
                    return $paymentStatus->errors;
                }
            }
        }
        return ['status' => true, 'iid' => $IID, 'rid' => $receipt->RID];
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
    
    public function actionViewMultipleClaim($rid){
        $receipt = Reciept::find()->with(['invoice', 'paymentStatus'])->where(['RID' => $rid])->one();
        
        $dateLists = InvoiceDescription::find()->select(['date'])->distinct()->where(['IID' => $receipt->IID])->orderBy(['date' => SORT_DESC])->all();
        $descriptions = InvoiceDescription::find()->where(['iid' => $receipt->IID, 'date' => $dateLists[0]])->all();
        
        return $this->render('view_multiple_claim', [
            'receipt' => $receipt,
            'descriptions' => $descriptions,
            'lastUpdate' => $dateLists[0],
        ]);
    }
    
    public function actionUpdateMultipleClaim($rid){
        $request = Yii::$app->request;
        $receipt = Reciept::find()->with(['invoice', 'paymentStatus'])->where(['RID' => $rid])->one();
        
        $dateLists = InvoiceDescription::find()->select(['date'])->distinct()->where(['IID' => $receipt->IID])->orderBy(['date' => SORT_DESC])->all();
        $descriptions = InvoiceDescription::find()->where(['iid' => $receipt->IID, 'date' => $dateLists[0]])->all();
        
        if($request->isAjax){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $descriptions = $request->post('invoice');
            $dt = date('Y-m-d H:i:s');
            foreach($descriptions as $description){
                $invoiceDescription = new InvoiceDescription();
                $invoiceDescription->IID = $receipt->IID;
                $invoiceDescription->description = $description['list'];
                $invoiceDescription->price = $description['price'];
                $invoiceDescription->date = $dt;
                
                if($invoiceDescription->validate()){
                    $invoiceDescription->save();
                }
                else{
                    return [
                        'status' => false,
                        'message' => 'validation error!',
                        'obj' => $invoiceDescription->errors
                    ];
                }
            }

            $receipt->total = InvoiceDescription::find()->where(['IID' => $receipt->IID, 'date' => $dt])->sum('price');
            if ($receipt->validate() ) {
                $receipt->save();
            }
            else {
                return [
                    'status' => false,
                    'message' => 'validation error!',
                    'obj' => $receipt->errors
                ];
            }

            // update 20170207
            $totalManual = $request->post('totalManual');
            if ($totalManual != null) {                
                $invoice = $receipt->invoice;
                $invoice->total = str_replace(",", "", $totalManual['total']);
                $invoice->total_vat = str_replace(",", "", $totalManual['total_tax']);
                $invoice->grand_total = str_replace(",", "", $totalManual['grandTotal']);
                if ($invoice->validate()) {
                    $invoice->save();
                }
                else {
                    return [
                        'status' => false,
                        'message' => 'validation error!',
                        'obj' => $invoice->errors
                    ];
                }
            }

            return [
                'status' => true, 
                'message' => 'Update successfully',
                'IID' => $invoice->IID
            ];
        }
        return $this->render('update_multiple_claim', [
            'receipt' => $receipt,
            'descriptions' => $descriptions,
            'lastUpdate' => $dateLists[0],
        ]);
    }
    
    public function actionSearch(){
        Yii::$app->formatter->nullDisplay = '-';
        $query = Reciept::find()->with('invoice')->orderBy(['RID' => SORT_DESC]);
        $dataProvider  = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        return $this->render('search',[
            'dataProvider' => $dataProvider,
        ]);
    }
}
?>
