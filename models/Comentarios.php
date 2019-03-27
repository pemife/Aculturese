<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comentarios".
 *
 * @property int $id
 * @property string $texto
 * @property string $created_at
 * @property int $usuario_id
 * @property int $padre_id
 * @property int $evento_id
 *
 * @property Comentarios $padre
 * @property Comentarios[] $comentarios
 * @property Eventos $evento
 * @property Usuarios $usuario
 */
class Comentarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comentarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['texto', 'usuario_id', 'evento_id'], 'required'],
            [['texto'], 'string'],
            [['created_at'], 'safe'],
            [['usuario_id', 'padre_id', 'evento_id'], 'default', 'value' => null],
            [['usuario_id', 'padre_id', 'evento_id'], 'integer'],
            [['padre_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comentarios::className(), 'targetAttribute' => ['padre_id' => 'id']],
            [['evento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Eventos::className(), 'targetAttribute' => ['evento_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'texto' => 'Texto',
            'created_at' => 'Created At',
            'usuario_id' => 'Usuario ID',
            'padre_id' => 'Padre ID',
            'evento_id' => 'Evento ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPadre()
    {
        return $this->hasOne(Comentarios::className(), ['id' => 'padre_id'])->inverseOf('comentarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::className(), ['padre_id' => 'id'])->inverseOf('padre');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvento()
    {
        return $this->hasOne(Eventos::className(), ['id' => 'evento_id'])->inverseOf('comentarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('comentarios');
    }
}
