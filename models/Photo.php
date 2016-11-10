<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "photo".
 *
 * @property integer $PID
 * @property integer $CLID
 * @property string $filename
 * @property string $last_update
 * @property string $type
 * @property integer $order
 *
 * @property Claim $cL
 */
class Photo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CLID'], 'required'],
            [['CLID', 'order'], 'integer'],
            [['filename'], 'string'],
            [['last_update'], 'safe'],
            [['type'], 'string', 'max' => 45],
            [['CLID'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::className(), 'targetAttribute' => ['CLID' => 'CLID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PID' => 'Pid',
            'CLID' => 'Clid',
            'filename' => 'Filename',
            'last_update' => 'Last Update',
            'type' => 'Type',
            'order' => 'Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCL()
    {
        return $this->hasOne(Claim::className(), ['CLID' => 'CLID']);
    }
}
