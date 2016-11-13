<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property integer $CID
 * @property string $fullname
 * @property string $type
 * @property string $address
 * @property integer $phone
 * @property integer $fax
 * @property integer $taxpayer_id
 *
 * @property Invoice[] $invoices
 * @property Quotation[] $quotations
 * @property Viecle[] $viecles
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'type', 'address'], 'string'],
            [['phone', 'fax', 'taxpayer_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CID' => 'Cid',
            'fullname' => 'ชื่อ',
            'type' => 'Type',
            'address' => 'ที่อยู่',
            'phone' => 'โทร',
            'fax' => 'แฟกซ์',
            'taxpayer_id' => 'เลขประจำตัวผู้เสียภาษี',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['CID' => 'CID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotations()
    {
        return $this->hasMany(Quotation::className(), ['CID' => 'CID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViecles()
    {
        return $this->hasMany(Viecle::className(), ['owner' => 'CID']);
    }
}
