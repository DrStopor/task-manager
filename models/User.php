<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $token_id
 * @property string $created_at
 * @property int $role_id
 *
 * @property Token $token
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'token_id'], 'required'],
            [['token_id'], 'default', 'value' => null],
            [['token_id', 'role_id'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 512],
            [['token_id'], 'exist', 'skipOnError' => true, 'targetClass' => Token::class, 'targetAttribute' => ['token_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'token_id' => 'Token ID',
            'created_at' => 'Created At',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * Gets query for [[Token]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getToken()
    {
        return $this->hasOne(Token::class, ['id' => 'token_id']);
    }

    /**
     * @param $id
     * @return IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @param $token
     * @param $type
     * @return IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $tokenId = Token::find()->select('id')->where(['token' => $token])->limit(1)->scalar();
        return static::findOne(['token_id' => $tokenId]);
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @param $authKey
     * @return bool|null
     */
    public function validateAuthKey($authKey)
    {
        return null;
    }

    public function getRole(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }
}
