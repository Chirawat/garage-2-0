<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "viecle_name".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Viecle[] $viecles
 * @property ViecleModel[] $viecleModels
 */
class ViecleName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'viecle_name';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViecles()
    {
        return $this->hasMany(Viecle::className(), ['viecle_name' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViecleModels()
    {
        return $this->hasMany(ViecleModel::className(), ['viecle_name' => 'id']);
    }
}
