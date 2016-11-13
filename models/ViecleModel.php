<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "viecle_model".
 *
 * @property integer $id
 * @property integer $viecle_name
 * @property string $model
 *
 * @property Viecle[] $viecles
 * @property ViecleName $viecleName
 */
class ViecleModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'viecle_model';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['viecle_name'], 'required'],
            [['viecle_name'], 'integer'],
            [['model'], 'string'],
            [['viecle_name'], 'exist', 'skipOnError' => true, 'targetClass' => ViecleName::className(), 'targetAttribute' => ['viecle_name' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'viecle_name' => 'ชื่อรถยนต์',
            'model' => 'ชื่อรุ่น',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViecles()
    {
        return $this->hasMany(Viecle::className(), ['viecle_model' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViecleName()
    {
        return $this->hasOne(ViecleName::className(), ['id' => 'viecle_name']);
    }
}
