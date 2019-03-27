<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios_etiquetas".
 *
 * @property int $usuario_id
 * @property int $etiqueta_id
 *
 * @property Etiquetas $etiqueta
 * @property Usuarios $usuario
 */
class UsuariosEtiquetas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios_etiquetas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'etiqueta_id'], 'required'],
            [['usuario_id', 'etiqueta_id'], 'default', 'value' => null],
            [['usuario_id', 'etiqueta_id'], 'integer'],
            [['usuario_id', 'etiqueta_id'], 'unique', 'targetAttribute' => ['usuario_id', 'etiqueta_id']],
            [['etiqueta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Etiquetas::className(), 'targetAttribute' => ['etiqueta_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usuario_id' => 'Usuario ID',
            'etiqueta_id' => 'Etiqueta ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtiqueta()
    {
        return $this->hasOne(Etiquetas::className(), ['id' => 'etiqueta_id'])->inverseOf('usuariosEtiquetas');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('usuariosEtiquetas');
    }
}
