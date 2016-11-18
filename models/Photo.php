<?php

namespace app\models;

use Yii;
use yii\helpers\BaseFileHelper;

/**
 * This is the model class for table "photo".
 *
 * @property integer $PID
 * @property integer $CLID
 * @property string $filename
 * @property string $last_update
 * @property string $type
 * @property integer $order
 *
 * @property Claim $cL
 */
class Photo extends \yii\db\ActiveRecord
{
    public $imageFiles;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CLID'], 'required'],
            [['CLID', 'order'], 'integer'],
            [['filename'], 'string'],
            [['last_update'], 'safe'],
            [['type'], 'string', 'max' => 45],
            [['CLID'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::className(), 'targetAttribute' => ['CLID' => 'CLID']],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg', 'maxFiles' => 4, 'maxSize'=>1024*1024*10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PID' => 'Pid',
            'CLID' => 'Clid',
            'filename' => 'Filename',
            'last_update' => 'Last Update',
            'type' => 'Type',
            'order' => 'Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClaim()
    {
        return $this->hasOne(Claim::className(), ['CLID' => 'CLID']);
    }

    public function upload($CLID, $claim_no, $type){
        if( $this->validate()){
            $path = 'upload/' . $CLID . '-' . $claim_no . '/' . $type;
            if( !is_dir($path) )
                $r = BaseFileHelper::createDirectory($path, 0777, true);
            
            foreach($this->imageFiles as $file){
                $file->saveAs( $path . '/' . $file->baseName . '.' . $file->extension );
            }
            return true;
        }
        else{
            return $this->errors;
        }
    }
}
