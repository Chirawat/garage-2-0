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

class ReceiptController extends Controller{
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

    public function actionReport($iid = null, $dateIndex = null){
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


        /* Update Reciept Info */
        $reciept = Reciept::find()->where(['IID' => $iid])->one();
//        var_dump( count($reciept) );
//        die();
        if( count($reciept) == null ){
            $reciept = new Reciept();
            
            $receiptId = Reciept::find()->where(['YEAR(date)' => date('Y')])->count();
            $receiptId = ($receiptId + 1) . "/" . (date('Y')+543);
            
            $reciept->reciept_id = $receiptId;
            $reciept->IID = $invoice->IID;

            $reciept->total = $grandTotal;
            $reciept->date = date('Y-m-d H:i:s');
            $reciept->EID = Yii::$app->user->identity->getId();

            if($reciept->validate() && $reciept->save()){
                // success
            }
            else{
                // failed
                var_dump( $reciept->errors );
                die();
            }    

        }
        
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
                'query' => Invoice::find()->with('reciept')->where(['between', 'UNIX_TIMESTAMP(date)', strtotime($startDate), strtotime($endDate)]),
                'pagination' => [
                'pageSize' => 20,
                ],
            ]);
        }
        else{
            $dataProvider = new ActiveDataProvider([
                'query' => Invoice::find()->with('reciept'),
                'pagination' => [
                'pageSize' => 20,
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
}
?>
