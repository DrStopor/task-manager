<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property string $ext_id
 * @property string $message
 * @property int $status_id
 * @property int $contact_id
 * @property int $user_id
 * @property string|null $comment
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Contact $contact
 * @property Status $status
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ext_id', 'message', 'status_id', 'contact_id', 'user_id'], 'required'],
            [['message', 'comment'], 'string'],
            [['status_id', 'contact_id', 'user_id'], 'default', 'value' => null],
            [['status_id', 'contact_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ext_id'], 'string', 'max' => 255],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::class, 'targetAttribute' => ['contact_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ext_id' => 'Ext ID',
            'message' => 'Message',
            'status_id' => 'Status ID',
            'contact_id' => 'Contact ID',
            'user_id' => 'User ID',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Contact]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::class, ['id' => 'contact_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }
}
