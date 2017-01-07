<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $IID
 * @property integer $CID
 * @property integer $VID
 * @property integer $CLID
 * @property string $invoice_id
 * @property string $date
 * @property integer $EID
 *
 * @property Employee $e
 * @property Claim $cL
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
            [['CID', 'EID'], 'required'],
            [['CID', 'VID', 'CLID', 'EID'], 'integer'],
            [['invoice_id', 'book_number'], 'string'],
            [['date'], 'safe'],
            [['total', 'total_vat', 'grand_total'], 'number'], 
            [['EID'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['EID' => 'EID']],
            [['CLID'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::className(), 'targetAttribute' => ['CLID' => 'CLID']],
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
            'CLID' => 'Clid',
            'invoice_id' => 'Invoice ID',
            'date' => 'Date',
            'EID' => 'Eid',
            'book_number' => 'เล่มที่',
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
    public function getClaim()
    {
        return $this->hasOne(Claim::className(), ['CLID' => 'CLID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['CID' => 'CID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViecle()
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
    public function getReciept()
    {
        return $this->hasOne(Reciept::className(), ['IID' => 'IID']);
    }
}
