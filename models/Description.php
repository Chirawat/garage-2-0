<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "description".
 *
 * @property integer $DID
 * @property integer $QID
 * @property integer $row
 * @property string $description
 * @property string $type
 * @property double $price
 *
 * @property Quotation $q
 */
class Description extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'description';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['QID'], 'required'],
            [['QID', 'row'], 'integer'],
            [['description', 'type'], 'string'],
            [['price'], 'number'],
            [['QID'], 'exist', 'skipOnError' => true, 'targetClass' => Quotation::className(), 'targetAttribute' => ['QID' => 'QID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DID' => 'Did',
            'QID' => 'Qid',
            'row' => 'Row',
            'description' => 'Description',
            'type' => 'Type',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQ()
    {
        return $this->hasOne(Quotation::className(), ['QID' => 'QID']);
    }
}
