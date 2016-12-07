<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_description_general".
 *
 * @property integer $idid
 * @property string $description
 * @property double $price
 * @property string $date
 * @property integer $IID
 *
 * @property InvoiceGeneral $i
 */
class InvoiceDescriptionGeneral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice_description_general';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['price'], 'number'],
            [['date'], 'safe'],
            [['IID'], 'required'],
            [['IID'], 'integer'],
            [['IID'], 'exist', 'skipOnError' => true, 'targetClass' => InvoiceGeneral::className(), 'targetAttribute' => ['IID' => 'IID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idid' => 'Idid',
            'description' => 'Description',
            'price' => 'Price',
            'date' => 'Date',
            'IID' => 'Iid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI()
    {
        return $this->hasOne(InvoiceGeneral::className(), ['IID' => 'IID']);
    }
}
