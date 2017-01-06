<?php

namespace app\models;

use Yii;

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
            [['CID', 'VID', 'TID', 'CLID', 'damage_level', 'damage_position', 'EID', 'revised'], 'integer'],
            [['quotation_id'], 'string'],
            [['quotation_date'], 'safe'],
            [['maintenance_total', 'part_total', 'total'], 'number'],
            [['CLID', 'damage_position', 'EID'], 'required'],
            [['EID'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['EID' => 'EID']],
            [['CLID'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::className(), 'targetAttribute' => ['CLID' => 'CLID']],
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
            'TID' => 'Tid',
            'quotation_id' => 'Quotation ID',
            'quotation_date' => 'Quotation Date',
            'CLID' => 'Clid',
            'damage_level' => 'Damage Level',
            'damage_position' => 'Damage Position',
            'EID' => 'Eid',
            'maintenance_total' => 'Maintenance Total', 
            'part_total' => 'Part Total', 
            'total' => 'Total', 
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
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['EID' => 'EID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClaim()
    {
        return $this->hasOne(Claim::className(), ['CLID' => 'CLID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
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
    public function getViecle()
    {
        return $this->hasOne(Viecle::className(), ['VID' => 'VID']);
    }
}
