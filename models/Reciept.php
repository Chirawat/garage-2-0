<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reciept".
 *
 * @property integer $RID
 * @property integer $IID
 * @property string $reciept_id
 * @property string $date
 * @property double $total
 * @property integer $EID
 *
 * @property Employee $e
 * @property Invoice $i
 */
class Reciept extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reciept';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IID'], 'required'],
            [['IID', 'EID'], 'integer'],
            [['reciept_id', 'book_number'], 'string'],
            [['date'], 'safe'],
            [['total'], 'number'],
            [['EID'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['EID' => 'EID']],
            [['IID'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['IID' => 'IID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'RID' => 'Rid',
            'IID' => 'Iid',
            'reciept_id' => 'เลขที่',
            'date' => 'วันที่',
            'total' => 'รวม',
            'EID' => 'Eid',
            'book_number' => 'เล่มที่',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['EID' => 'EID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['IID' => 'IID']);
    }
    
    public function getPaymentStatus()
    {
       return $this->hasMany(PaymentStatus::className(), ['RID' => 'RID']);
    }
}
