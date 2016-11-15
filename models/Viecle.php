<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "viecle".
 *
 * @property integer $VID
 * @property string $plate_no
 * @property integer $viecle_name
 * @property integer $viecle_model
 * @property string $body_code
 * @property string $engin_code
 * @property integer $viecle_year
 * @property integer $body_type
 * @property integer $cc
 * @property integer $seat
 * @property integer $weight
 * @property integer $owner
 *
 * @property Quotation[] $quotations
 * @property BodyType $bodyType
 * @property Customer $owner0
 * @property ViecleModel $viecleModel
 * @property ViecleName $viecleName
 */
class Viecle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'viecle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plate_no'], 'required'],
            [['plate_no', 'body_code', 'engin_code', 'body_type'], 'string'],
            [['viecle_name', 'viecle_model', 'viecle_year', 'cc', 'seat', 'weight', 'owner'], 'integer'],
            //[['body_type'], 'exist', 'skipOnError' => true, 'targetClass' => BodyType::className(), 'targetAttribute' => ['body_type' => 'id']],
            [['owner'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['owner' => 'CID']],
            [['viecle_model'], 'exist', 'skipOnError' => true, 'targetClass' => ViecleModel::className(), 'targetAttribute' => ['viecle_model' => 'id']],
            [['viecle_name'], 'exist', 'skipOnError' => true, 'targetClass' => ViecleName::className(), 'targetAttribute' => ['viecle_name' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VID' => 'Vid',
            'plate_no' => 'เลขทะเบียน',
            'viecle_name' => 'Viecle Name',
            'viecle_model' => 'Viecle Model',
            'body_code' => 'หมายเลขตัวถัง',
            'engin_code' => 'หมายเลขเครื่องยนต์',
            'viecle_year' => 'Viecle Year',
            'body_type' => 'Body Type',
            'cc' => 'Cc',
            'seat' => 'Seat',
            'weight' => 'Weight',
            'owner' => 'ชื่อผู้เอาประกัน',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotations()
    {
        return $this->hasMany(Quotation::className(), ['VID' => 'VID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBodyType()
    {
        return $this->hasOne(BodyType::className(), ['id' => 'body_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner0()
    {
        return $this->hasOne(Customer::className(), ['CID' => 'owner']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViecleModel()
    {
        return $this->hasOne(ViecleModel::className(), ['id' => 'viecle_model']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViecleName()
    {
        return $this->hasOne(ViecleName::className(), ['id' => 'viecle_name']);
    }
}
