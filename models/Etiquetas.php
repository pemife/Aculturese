<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "etiquetas".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property EventosEtiquetas[] $eventosEtiquetas
 * @property Eventos[] $eventos
 * @property UsuariosEtiquetas[] $usuariosEtiquetas
 * @property Usuarios[] $usuarios
 */
class Etiquetas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'etiquetas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventosEtiquetas()
    {
        return $this->hasMany(EventosEtiquetas::className(), ['etiqueta_id' => 'id'])->inverseOf('etiqueta');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Eventos::className(), ['id' => 'evento_id'])->viaTable('eventos_etiquetas', ['etiqueta_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuariosEtiquetas()
    {
        return $this->hasMany(UsuariosEtiquetas::className(), ['etiqueta_id' => 'id'])->inverseOf('etiqueta');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['id' => 'usuario_id'])->viaTable('usuarios_etiquetas', ['etiqueta_id' => 'id']);
    }
}
