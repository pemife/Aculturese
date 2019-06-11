<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eventos".
 *
 * @property int $id
 * @property string $nombre
 * @property string $inicio
 * @property string $fin
 * @property bool $es_privado
 * @property int $lugar_id
 * @property int $categoria_id
 *
 * @property Comentarios[] $comentarios
 * @property Categorias $categoria
 * @property Lugares $lugar
 * @property EventosEtiquetas[] $eventosEtiquetas
 * @property Etiquetas[] $etiquetas
 * @property UsuariosEventos[] $usuariosEventos
 * @property Usuarios[] $usuarios
 */
class Eventos extends \yii\db\ActiveRecord
{
    public $imagen;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'inicio', 'fin', 'categoria_id'], 'required'],
            ['inicio', 'datetime', 'format' => 'php:Y-m-d H:i'],
            ['fin', 'datetime', 'format' => 'php:Y-m-d H:i'],
            ['inicio', 'compare', 'compareAttribute' => 'fin', 'operator' => '<', 'enableClientValidation' => false],
            [['lugar_id', 'categoria_id'], 'default', 'value' => null],
            [['lugar_id', 'categoria_id'], 'integer'],
            [['es_privado'], 'boolean'],
            [['nombre'], 'string', 'max' => 255],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorias::className(), 'targetAttribute' => ['categoria_id' => 'id']],
            [['lugar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lugares::className(), 'targetAttribute' => ['lugar_id' => 'id']],
            [['imagen'], 'file', 'extensions' => 'jpg'],
            [['creador_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['creador_id' => 'id']],
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
            'inicio' => 'Inicio',
            'fin' => 'Fin',
            'lugar_id' => 'Lugar',
            'categoria_id' => 'Categoria',
            'creador_id' => 'Creador',
            'es_privado' => 'Es privado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreador()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'creador_id'])->inverseOf('eventos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::className(), ['evento_id' => 'id'])->inverseOf('evento');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categorias::className(), ['id' => 'categoria_id'])->inverseOf('eventos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLugar()
    {
        return $this->hasOne(Lugares::className(), ['id' => 'lugar_id'])->inverseOf('eventos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtiquetas()
    {
        return $this->hasMany(Etiquetas::className(), ['id' => 'etiqueta_id'])->viaTable('eventos_etiquetas', ['evento_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['id' => 'usuario_id'])->viaTable('usuarios_eventos', ['evento_id' => 'id']);
    }

    public function getUrlImagen()
    {
        return $this->tieneImagen() ? Yii::getAlias('@uploadsUrl/' . $this->id . '.jpg') : null;
    }

    public function tieneImagen()
    {
        return file_exists(Yii::getAlias('@uploads/' . $this->id . '.jpg'));
    }

    public function esAsistente($usuario)
    {
        return in_array($usuario, $this->usuarios);
    }
}
