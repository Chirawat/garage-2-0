<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $IID
 * @property integer $CID
 * @property integer $VID
 * @property string $claim_no
 * @property string $invoice_id
 * @property string $date
 * @property integer $EID
 *
 * @property Employee $e
 * @property Customer $c
 * @property Viecle $v
 * @property InvoiceDescription[] $invoiceDescriptions
 * @property Reciept[] $reciepts
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CID', 'VID', 'EID'], 'required'],
            [['CID', 'VID', 'EID'], 'integer'],
            [['claim_no', 'invoice_id'], 'string'],
            [['date'], 'safe'],
            [['EID'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['EID' => 'EID']],
            [['CID'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['CID' => 'CID']],
            [['VID'], 'exist', 'skipOnError' => true, 'targetClass' => Viecle::className(), 'targetAttribute' => ['VID' => 'VID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IID' => 'Iid',
            'CID' => 'Cid',
            'VID' => 'Vid',
            'claim_no' => 'Claim No',
            'invoice_id' => 'Invoice ID',
            'date' => 'Date',
            'EID' => 'Eid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE()
    {
        return $this->hasOne(Employee::className(), ['EID' => 'EID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(Customer::className(), ['CID' => 'CID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getV()
    {
        return $this->hasOne(Viecle::className(), ['VID' => 'VID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceDescriptions()
    {
        return $this->hasMany(InvoiceDescription::className(), ['IID' => 'IID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReciepts()
    {
        return $this->hasMany(Reciept::className(), ['IID' => 'IID']);
    }
}
