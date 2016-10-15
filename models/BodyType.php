<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "body_type".
 *
 * @property integer $id
 * @property string $type
 *
 * @property Viecle[] $viecles
 */
class BodyType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'body_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViecles()
    {
        return $this->hasMany(Viecle::className(), ['body_type' => 'id']);
    }
}
