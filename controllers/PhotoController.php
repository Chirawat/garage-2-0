<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Claim;
use app\models\Photo;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

class PhotoController extends Controller
{
    public $types_t = ['BEFORE', 'DURING', 'COMPARE', 'AFTER', 'OTHER'];

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


    function detailRender($CLID=null, $type=null){
        $details = Photo::find()->where(['CLID' => $CLID, 'type' => $type])->orderBy('order')->all();

        return $content = $this->renderPartial('detail', [
            'type' => $type,
            'details' => $details,
        ]);
    }

    public function actionIndex(){
        $request = Yii::$app->request;

        $claim = new Claim();

        $photo = new Photo();

        $claim_t  = Claim::find()->all();

        $selectedKey = 0;
        if($request->isGet){
            $CLID = $request->get('CLID');

            // query photo corresponding to CLID, type = BEFORE
            $selectedType = $request->get('type');
            $selectedKey = $CLID;

            $details = $this->detailRender($CLID, $selectedType);

        }

        // when upload file
        if($request->isPost){
            $photo->load( $request->post() );
            $photo->imageFile = UploadedFile::getInstance($photo, 'imageFile');
            $photo->filename = $photo->imageFile->name;
            $photo->last_update = date('Y-m-d H:i:s');
            $photo->order = Photo::find()->where(['CLID' => $photo->CLID, 'type' => $photo->type])->count() + 1;

            $photo->save();
            $photo->upload( $photo->claim['CLID'], $photo->claim['claim_no'], $photo->type);

            return $this->redirect(['photo/index', 'CLID' => $photo->CLID, 'type' => $photo->type]);
        }

        return $this->render('index', [
            'claim' => $claim,
            'claim_t' => $claim_t,
            'selectedKey' => $selectedKey,
            'selectedType' => $selectedType,
            'photo' => $photo,

            'details' => $details,
        ]);
    }

    public function actionDetail($CLID=null, $type=null){
        $content = $this->detailRender($CLID, $type);
        echo $content;
    }
    
    public function actionDel(){
        $request = Yii::$app->request;

        if($request->isPost){
            $CLID = null;
            $type = null;
            $PID = $request->post('PID');
            foreach($PID as $PID_t){
                $photo = Photo::findOne($PID_t);

                $CLID = $photo->CLID;
                $type = $photo->type;
                $file = 'upload/' . $photo->CLID . '-' . $photo->claim['claim_no'] . '/' . $photo->type . '/' . $photo->filename;
                if(file_exists($file))
                    unlink($file);
                $photo->delete();
            }

            $this->redirect(['photo/index', 'CLID' => $CLID, 'type' => $type]);
        }
    }

    public function typeStr($type){
        switch($type){
            case 'BEFORE':
                return 'ภาพก่อนซ่อม';
            case 'DURING':
                return 'ภาพขณะซ่อม';
            case 'COMPARE':
                return 'เทียบอะไหล่';
            case 'AFTER':
                return 'ซ่อมเสร็จ';
            case 'OTHER':
                return 'อื่น ๆ';
        }
    }
    public function actionReport($CLID=null, $type=null){
        $content = null;
        if($type != null){
            $details = Photo::find()->where(['CLID' => $CLID, 'type' => $type])->all();
            $claim_no = Claim::findOne($CLID)['claim_no'];
            $content = $this->renderPartial('report', [
                'claim_no' => $claim_no,
                'type' => $this->typeStr($type),
                'details' => $details,
            ]);
        }
        else{
            $page = 1;
            foreach($this->types_t as $type_t){
                $details = Photo::find()->where(['CLID' => $CLID, 'type' => $type_t])->all();
                $claim_no = Claim::findOne($CLID)['claim_no'];
                if( sizeof($details) != 0 ){
                    if($page != 1)
                        $content .= "<pagebreak />";
                     $content .= $this->renderPartial('report', [
                        'claim_no' => $claim_no,
                        'type' => $this->typeStr($type_t),
                        'details' => $details,
                    ]);
                }
                $page++;
            }
        }

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
            //'SetHeader'=>['หมายเลขเคลม XXXX / ประเภท XXXX'],
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
