<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "claim".
 *
 * @property integer $CLID
 * @property string $claim_no
 *
 * @property Invoice[] $invoices
 * @property PaymentStatus[] $paymentStatuses
 * @property Photo[] $photos
 * @property Quotation[] $quotations
 */
class Claim extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'claim';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['claim_no'], 'required'],
            [['claim_no'], 'string'],
            [['create_time'], 'safe'],
            [['VID'], 'exist', 'skipOnError' => true, 'targetClass' => Viecle::className(), 'targetAttribute' => ['VID' => 'VID']], 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CLID' => 'Clid',
            'claim_no' => 'หมายเลขเคลม',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['CLID' => 'CLID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentStatus()
    {
        return $this->hasOne(PaymentStatus::className(), ['CLID' => 'CLID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['CLID' => 'CLID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotations()
    {
        return $this->hasMany(Quotation::className(), ['CLID' => 'CLID']);
    }
    
    public function getViecle() 
    { 
       return $this->hasOne(Viecle::className(), ['VID' => 'VID']); 
    }
    
    public function getInvoiceGeneral()
    {
       return $this->hasOne(InvoiceGeneral::className(), ['CLID' => 'CLID']);
    }
}
