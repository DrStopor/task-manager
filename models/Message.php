<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $ext_id
 * @property string $message
 * @property int $status_id
 * @property int $contact_id
 * @property int $user_id
 * @property string|null $comment
 * @property string $created_at
 * @property string|null $updated_at
 * @property bool|null $is_sent
 * @property string|null $time_sent
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
            [['id'], 'unique'],
            [['ext_id', 'message', 'contact_id'], 'required'],
            [['message', 'comment'], 'string'],
            [['status_id', 'contact_id', 'user_id'], 'default', 'value' => null],
            [['status_id', 'contact_id', 'user_id'], 'integer'],
            [['status_id'], 'default', 'value' => 1],
            [['created_at', 'updated_at', 'time_sent'], 'safe'],
            [['is_sent'], 'boolean'],
            [['ext_id'], 'string', 'max' => 255],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::class, 'targetAttribute' => ['contact_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
            [['user_id', 'comment', 'updated_at'], 'required', 'when' => function ($model) {
                return $model->status_id === 2;
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Message ID',
            'ext_id' => 'Ext ID',
            'message' => 'Message',
            'status_id' => 'Status ID',
            'contact_id' => 'Contact ID',
            'user_id' => 'User ID',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_sent' => 'Is Sent',
            'time_sent' => 'Time Sent',
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
