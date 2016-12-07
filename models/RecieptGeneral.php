<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reciept_general".
 *
 * @property integer $RID
 * @property string $book_number
 * @property string $reciept_id
 * @property string $date
 * @property integer $total
 * @property integer $EID
 * @property integer $IID
 *
 * @property Employee $e
 * @property InvoiceGeneral $i
 */
class RecieptGeneral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reciept_general';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_number', 'reciept_id'], 'string'],
            [['date'], 'safe'],
            [['total', 'EID', 'IID'], 'integer'],
            [['IID'], 'required'],
            [['EID'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['EID' => 'EID']],
            [['IID'], 'exist', 'skipOnError' => true, 'targetClass' => InvoiceGeneral::className(), 'targetAttribute' => ['IID' => 'IID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RID' => 'Rid',
            'book_number' => 'Book Number',
            'reciept_id' => 'Reciept ID',
            'date' => 'Date',
            'total' => 'Total',
            'EID' => 'Eid',
            'IID' => 'Iid',
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
    public function getI()
    {
        return $this->hasOne(InvoiceGeneral::className(), ['IID' => 'IID']);
    }
}
