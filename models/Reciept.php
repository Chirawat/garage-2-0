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
 * @property integer $total
 * @property integer $receive
 * @property integer $Employee_EID
 *
 * @property Employee $employeeE
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
            [['IID', 'Employee_EID'], 'required'],
            [['IID', 'total', 'receive', 'Employee_EID'], 'integer'],
            [['reciept_id'], 'string'],
            [['date'], 'safe'],
            [['Employee_EID'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['Employee_EID' => 'EID']],
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
            'reciept_id' => 'Reciept ID',
            'date' => 'Date',
            'total' => 'Total',
            'receive' => 'Receive',
            'Employee_EID' => 'Employee  Eid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeE()
    {
        return $this->hasOne(Employee::className(), ['EID' => 'Employee_EID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI()
    {
        return $this->hasOne(Invoice::className(), ['IID' => 'IID']);
    }
}
