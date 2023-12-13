<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mail_log".
 *
 * @property string $from
 * @property string $to
 * @property string|null $cc
 * @property string|null $bcc
 * @property string $subject
 * @property string $body
 * @property string|null $attachment
 * @property int $status
 * @property string $created_at
 * @property string|null $updated_at
 * @property int $message_id
 * @property string|null $error
 * @property bool $is_sent
 */
class MailLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mail_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to', 'subject', 'body', 'status', 'message_id'], 'required'],
            [['body', 'error'], 'string'],
            [['status', 'message_id'], 'default', 'value' => null],
            [['status', 'message_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_sent'], 'boolean'],
            [['from', 'to', 'cc', 'bcc', 'subject', 'attachment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'from' => 'From',
            'to' => 'To',
            'cc' => 'Cc',
            'bcc' => 'Bcc',
            'subject' => 'Subject',
            'body' => 'Body',
            'attachment' => 'Attachment',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'message_id' => 'Message ID',
            'error' => 'Error',
            'is_sent' => 'Is Sent',
        ];
    }
}
