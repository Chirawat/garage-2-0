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

    public function actionIndex(){
        $request = Yii::$app->request;

        $claim = new Claim();

        $photo = new Photo();

        $claim_t  = Claim::find()->all();
        if($CLID = $request->get('CLID')){
            $selectedKey = $CLID;
        }
        else{
            $selectedKey = $claim_t[0]->CLID;
        }

        if($request->isPost){
            $photo->load( $request->post() );
            $photo->imageFile = UploadedFile::getInstance($photo, 'imageFile');
            $photo->filename = $photo->imageFile->name;
            $photo->last_update = date('Y-m-d H:i:s');
            $photo->order = Photo::find()->where(['CLID' => $photo->CLID, 'type' => $photo->type])->count() + 1;

            $photo->save();
            $photo->upload( $photo->claim['CLID'], $photo->claim['claim_no']);

            return $this->redirect(['photo/index', 'CLID' => $photo->CLID]);
        }

        return $this->render('index', [
            'claim' => $claim,
            'claim_t' => $claim_t,
            'selectedKey' => $selectedKey,

            'photo' => $photo,
        ]);
    }

    public function view($CLID){

    }

    public function actionDetail($CLID=null, $type=null){
        $photo = Photo::find()->where(['CLID' => $CLID, 'type' => $type])->all();

    }
}
