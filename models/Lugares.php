<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lugares".
 *
 * @property int $id
 * @property string $lat
 * @property string $lon
 * @property string $nombre
 *
 * @property Eventos[] $eventos
 */
class Lugares extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lugares';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lat', 'lon'], 'required'],
            [['lat', 'lon'], 'number'],
            [['nombre'], 'string', 'max' => 32],
            [['lat', 'lon'], 'unique', 'targetAttribute' => ['lat', 'lon']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Eventos::className(), ['lugar_id' => 'id'])->inverseOf('lugar');
    }
}
