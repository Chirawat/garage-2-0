<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property integer $EID
 * @property string $fullname
 * @property string $Position
 *
 * @property Invoice[] $invoices
 * @property Quotation[] $quotations
 * @property Reciept[] $reciepts
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'Position'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'EID' => 'Eid',
            'fullname' => 'Fullname',
            'Position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['EID' => 'EID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotations()
    {
        return $this->hasMany(Quotation::className(), ['EID' => 'EID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReciepts()
    {
        return $this->hasMany(Reciept::className(), ['Employee_EID' => 'EID']);
    }
}
