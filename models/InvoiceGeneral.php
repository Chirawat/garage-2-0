<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_general".
 *
 * @property integer $IID
 * @property integer $CID
 * @property integer $VID
 * @property integer $CLID
 * @property string $book_number
 * @property string $invoice_id
 * @property string $date
 * @property integer $EID
 *
 * @property InvoiceDescriptionGeneral[] $invoiceDescriptionGenerals
 * @property Employee $e
 * @property Claim $cL
 * @property Customer $c
 * @property Viecle $v
 * @property RecieptGeneral[] $recieptGenerals
 */
class InvoiceGeneral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_general';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CID', 'EID'], 'required'],
            [['CID', 'VID', 'CLID', 'EID'], 'integer'],
            [['book_number', 'invoice_id'], 'string'],
            [['date'], 'safe'],
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
            'book_number' => 'Book Number',
            'invoice_id' => 'Invoice ID',
            'date' => 'Date',
            'EID' => 'Eid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceDescriptionGenerals()
    {
        return $this->hasMany(InvoiceDescriptionGeneral::className(), ['IID' => 'IID']);
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
    public function getCL()
    {
        return $this->hasOne(Claim::className(), ['CLID' => 'CLID']);
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
    public function getRecieptGenerals()
    {
        return $this->hasMany(RecieptGeneral::className(), ['IID' => 'IID']);
    }
}
