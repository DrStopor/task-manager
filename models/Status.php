<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status".
 *
 * @property int $id
 * @property string $name
 *
 * @property MessageActive[] $messageActives
 * @property MessageResolve[] $messageResolves
 * @property Message[] $messages
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * Gets query for [[MessageActives]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessageActives()
    {
        return $this->hasMany(MessageActive::class, ['status_id' => 'id']);
    }

    /**
     * Gets query for [[MessageResolves]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessageResolves()
    {
        return $this->hasMany(MessageResolve::class, ['status_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['status_id' => 'id']);
    }
}
