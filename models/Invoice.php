<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $IID
 * @property integer $CID
 * @property string $invoice_id
 * @property string $date
 * @property integer $employee
 *
 * @property Customer $c
 * @property InvoiceDescription[] $invoiceDescriptions
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
            [['CID'], 'required'],
            [['CID', 'employee'], 'integer'],
            [['invoice_id'], 'string'],
            [['date'], 'safe'],
            [['CID'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['CID' => 'CID']],
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
            'invoice_id' => 'Invoice ID',
            'date' => 'Date',
            'employee' => 'Employee',
        ];
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
    public function getInvoiceDescriptions()
    {
        return $this->hasMany(InvoiceDescription::className(), ['IID' => 'IID']);
    }
}
