<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "damage_position".
 *
 * @property integer $id
 * @property string $position
 *
 * @property Quotation[] $quotations
 */
class DamagePosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'damage_position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotations()
    {
        return $this->hasMany(Quotation::className(), ['damage_position' => 'id']);
    }
}
