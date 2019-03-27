<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eventos_etiquetas".
 *
 * @property int $evento_id
 * @property int $etiqueta_id
 *
 * @property Etiquetas $etiqueta
 * @property Eventos $evento
 */
class EventosEtiquetas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eventos_etiquetas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['evento_id', 'etiqueta_id'], 'required'],
            [['evento_id', 'etiqueta_id'], 'default', 'value' => null],
            [['evento_id', 'etiqueta_id'], 'integer'],
            [['evento_id', 'etiqueta_id'], 'unique', 'targetAttribute' => ['evento_id', 'etiqueta_id']],
            [['etiqueta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Etiquetas::className(), 'targetAttribute' => ['etiqueta_id' => 'id']],
            [['evento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Eventos::className(), 'targetAttribute' => ['evento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'evento_id' => 'Evento ID',
            'etiqueta_id' => 'Etiqueta ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtiqueta()
    {
        return $this->hasOne(Etiquetas::className(), ['id' => 'etiqueta_id'])->inverseOf('eventosEtiquetas');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvento()
    {
        return $this->hasOne(Eventos::className(), ['id' => 'evento_id'])->inverseOf('eventosEtiquetas');
    }
}
