<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "quotation".
 *
 * @property integer $QID
 * @property integer $CID
 * @property integer $VID
 * @property string $Employee
 * @property integer $TID
 * @property string $quotation_id
 * @property string $quotation_date
 * @property string $claim_no
 * @property integer $damage_level
 * @property integer $damage_position
 *
 * @property Description[] $descriptions
 * @property Customer $c
 * @property DamagePosition $damagePosition
 * @property Viecle $v
 */
class Quotation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quotation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CID', 'VID', 'TID', 'damage_level', 'damage_position'], 'integer'],
            [['Employee', 'quotation_id', 'claim_no'], 'string'],
            [['quotation_date'], 'safe'],
            [['damage_position'], 'required'],
            [['CID'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['CID' => 'CID']],
            [['damage_position'], 'exist', 'skipOnError' => true, 'targetClass' => DamagePosition::className(), 'targetAttribute' => ['damage_position' => 'id']],
            [['VID'], 'exist', 'skipOnError' => true, 'targetClass' => Viecle::className(), 'targetAttribute' => ['VID' => 'VID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'QID' => 'Qid',
            'CID' => 'Cid',
            'VID' => 'Vid',
            'Employee' => 'Employee',
            'TID' => 'Tid',
            'quotation_id' => 'Quotation ID',
            'quotation_date' => 'Quotation Date',
            'claim_no' => 'Claim No',
            'damage_level' => 'Damage Level',
            'damage_position' => 'Damage Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescriptions()
    {
        return $this->hasMany(Description::className(), ['QID' => 'QID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(Customer::className(), ['CID' => 'CID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDamagePosition()
    {
        return $this->hasOne(DamagePosition::className(), ['id' => 'damage_position']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getV()
    {
        return $this->hasOne(Viecle::className(), ['VID' => 'VID']);
    }
}
