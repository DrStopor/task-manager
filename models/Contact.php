<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 *
 * @property MessageActive[] $messageActives
 * @property MessageResolve[] $messageResolves
 * @property Message[] $messages
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['name', 'email'], 'string', 'max' => 255],
            [['email'], 'unique'],
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
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[MessageActives]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessageActives()
    {
        return $this->hasMany(MessageActive::class, ['contact_id' => 'id']);
    }

    /**
     * Gets query for [[MessageResolves]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessageResolves()
    {
        return $this->hasMany(MessageResolve::class, ['contact_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['contact_id' => 'id']);
    }
}
