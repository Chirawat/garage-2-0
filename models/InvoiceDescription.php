<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_description".
 *
 * @property integer $idid
 * @property integer $IID
 * @property string $description
 * @property integer $price
 *
 * @property Invoice $i
 */
class InvoiceDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_description';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IID'], 'required'],
            [['IID', 'price'], 'integer'],
            [['description'], 'string'],
            [['IID'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['IID' => 'IID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idid' => 'Idid',
            'IID' => 'Iid',
            'description' => 'Description',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI()
    {
        return $this->hasOne(Invoice::className(), ['IID' => 'IID']);
    }
}
