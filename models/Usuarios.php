<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $password
 * @property string $created_at
 * @property string $token
 * @property string $email
 * @property string $biografia
 * @property string $fechanac
 *
 * @property Comentarios[] $comentarios
 * @property UsuariosEtiquetas[] $usuariosEtiquetas
 * @property Etiquetas[] $etiquetas
 */
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    //const SCENARIO_MODPERFIL = 'modperfil';

    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'email'], 'required'],
            [['fechanac', 'created_at'], 'safe'],
            [['biografia'], 'string'],
            [['nombre'], 'string', 'max' => 32],
            ['nombre', 'filter', 'filter' => 'trim'],
            [['password', 'password_repeat'], 'string', 'max' => 60],
            [['password', 'password_repeat', 'email'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['email'], 'required', 'on' => [self::SCENARIO_UPDATE]],
            [['password'], 'compare', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['nombre'], 'unique'],
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
            'password' => 'Contraseña',
            'password_repeat' => 'Repite contraseña',
            'created_at' => 'Miembro desde',
            'token' => 'Token',
            'email' => 'Email',
            'biografia' => 'Biografia',
            'fechanac' => 'Fechanac',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::className(), ['usuario_id' => 'id'])->inverseOf('usuario');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtiquetas()
    {
        return $this->hasMany(Etiquetas::className(), ['id' => 'etiqueta_id'])->viaTable('usuarios_etiquetas', ['usuario_id' => 'id']);
    }

    public function getEventos()
    {
        return $this->hasMany(Eventos::className(), ['id' => 'evento_id'])->viaTable('usuarios_eventos', ['usuario_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmigos()
    {
        return $this->hasMany(self::className(), ['id' => 'amigo_id'])->viaTable('amigos', ['usuario_id' => 'id']);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @param null|mixed $type
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
    }

    /**
     * Validates password.
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            if ($this->scenario === self::SCENARIO_CREATE) {
                goto salto;
            }
        } elseif ($this->scenario === self::SCENARIO_UPDATE) {
            if ($this->password === '') {
                $this->password = $this->getOldAttribute('password');
            } else {
                salto:
                $this->password = Yii::$app->security
                    ->generatePasswordHash($this->password);
            }
        }

        $this->nombre = str_replace(' ', '', $this->nombre);

        return true;
    }

    public function creaToken()
    {
        return Yii::$app->security->generateRandomString(32);
    }

    public function asistire($usuarioId, $eventoId)
    {
        if (!Yii::$app->user->isGuest) {
            $sql = 'insert into usuarios_eventos(usuario_id, evento_id) values(' . $usuarioId . ', ' . $eventoId . ')';
            if (Yii::$app->db->createCommand($sql)->execute()) {
                return true;
            }
            Yii::$app->session->setFlash('error', 'Ha habido un error al unirte');
            return false;
        }
        Yii::$app->session->setFlash('error', 'Debes estar logeado para marcarte como asistente');
        return false;
    }

    public function esAmigo($usuarioId, $amigoId)
    {
        $usuario = self::findOne($usuarioId);
        $amigo = self::findOne($amigoId);
        return in_array($usuario, $amigo->amigos);
    }

    public function anadirAmigo($usuarioId, $amigoId)
    {
        if (!$this->esAmigo($usuarioId, $amigoId)) {
            $sql = 'insert into amigos(usuario_id, amigo_id) values(' . $usuarioId . ', ' . $amigoId . '), (' . $amigoId . ', ' . $usuarioId . ')';
            if (Yii::$app->db->createCommand($sql)->execute()) {
                Yii::$app->session->setFlash('info', 'Te has añadido satisfactoriamente como amigo');
                return true;
            }
            Yii::$app->session->setFlash('error', 'Ha habido un error al añadirte como amigo');
            return false;
        }
        Yii::$app->session->setFlash('error', 'Ya sois amigos!');
        return false;
    }

    public function borrarAmigo($usuarioId, $amigoId)
    {
        if ($this->esAmigo($usuarioId, $amigoId)) {
            $sql = 'delete from amigos where (usuario_id = ' . $usuarioId . ' and amigo_id =' . $amigoId . ') or (amigo_id =' . $usuarioId . ' and usuario_id = ' . $amigoId . ')';
            if (Yii::$app->db->createCommand($sql)->execute()) {
                Yii::$app->session->setFlash('info', 'Te has borrado satisfactoriamente como amigo');
                return true;
            }
            Yii::$app->session->setFlash('error', 'Ha habido un error al borrarte como amigo');
            return false;
        }
        Yii::$app->session->setFlash('error', 'No sois amigos!');
        return false;
    }
  
    public function borrarme($usuarioId, $eventoId)
    {
        if (!Yii::$app->user->isGuest) {
            $sql = 'delete from usuarios_eventos where (usuario_id=' . $usuarioId . ') and (evento_id=' . $eventoId . ')';
            if (Yii::$app->db->createCommand($sql)->execute()) {
                return true;
            }
            Yii::$app->session->setFlash('error', 'Ha habido un error al borrarte');
            return false;
        }
        Yii::$app->session->setFlash('error', 'Debes estar logeado para marcarte como asistente');
        return false;
    }

    public function esAsistente($usuarioId, $eventoId)
    {
        // return in_array(Eventos::find($eventoId)->one(), $this->findModel($usuarioId)->eventos);

        foreach ($this::findModel($usuarioId)->eventos as $evento) {
            if ($evento->getAttributes() === Eventos::findOne($eventoId)->getAttributes()) {
                return  true;
            }
        }
        return false;
    }

    public function findModel($id)
    {
        return $this->findOne($id);
    }
}
