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
 * @property int $lugar_id
 * @property int $categoria_id
 *
 * @property Comentarios[] $comentarios
 * @property Categorias $categoria
 * @property Lugares $lugar
 * @property EventosEtiquetas[] $eventosEtiquetas
 * @property Etiquetas[] $etiquetas
 */
class Eventos extends \yii\db\ActiveRecord
{
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
            [['inicio', 'fin'], 'safe'],
            [['lugar_id', 'categoria_id'], 'default', 'value' => null],
            [['lugar_id', 'categoria_id'], 'integer'],
            [['nombre'], 'string', 'max' => 255],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorias::className(), 'targetAttribute' => ['categoria_id' => 'id']],
            [['lugar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lugares::className(), 'targetAttribute' => ['lugar_id' => 'id']],
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
            'lugar_id' => 'Lugar ID',
            'categoria_id' => 'Categoria ID',
        ];
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
    public function getEventosEtiquetas()
    {
        return $this->hasMany(EventosEtiquetas::className(), ['evento_id' => 'id'])->inverseOf('evento');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtiquetas()
    {
        return $this->hasMany(Etiquetas::className(), ['id' => 'etiqueta_id'])->viaTable('eventos_etiquetas', ['evento_id' => 'id']);
    }
}
