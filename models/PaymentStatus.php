<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_status".
 *
 * @property integer $id
 * @property integer $RID
 * @property integer $CLID
 *
 * @property Reciept $r
 * @property Claim $cL
 */
class PaymentStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RID', 'CLID'], 'required'],
            [['RID', 'CLID'], 'integer'],
            [['CLID'], 'unique'],
            [['RID'], 'exist', 'skipOnError' => true, 'targetClass' => Reciept::className(), 'targetAttribute' => ['RID' => 'RID']],
            [['CLID'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::className(), 'targetAttribute' => ['CLID' => 'CLID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'RID' => 'Rid',
            'CLID' => 'Clid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceipt()
    {
        return $this->hasOne(Reciept::className(), ['RID' => 'RID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClaim()
    {
        return $this->hasOne(Claim::className(), ['CLID' => 'CLID']);
    }
}
