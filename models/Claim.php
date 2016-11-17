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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CLID' => 'Clid',
            'claim_no' => 'Claim No',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['CLID' => 'CLID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentStatus()
    {
        return $this->hasMany(PaymentStatus::className(), ['CLID' => 'CLID']);
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
}
