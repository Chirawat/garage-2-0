<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Claim;
use app\models\Photo;
use yii\web\UploadedFile;

class PhotoController extends Controller
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
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $details = Photo::find()->where(['CLID' => $CLID, 'type' => $type])->all();

//        return $details;

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
            $details = $this->detailRender($CLID, 'BEFORE');
            $selectedKey = $CLID;

            $selectedType = $request->get('type');
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
        //$details = Photo::find()->where(['CLID' => $CLID, 'type' => $type])->all();
        $content = $details = $this->detailRender($CLID, $type);
        echo $content;
    }

}
