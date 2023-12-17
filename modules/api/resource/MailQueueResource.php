<?php

namespace app\modules\api\resource;

use app\models\MailQueue;

/**
 * Class MailQueueResource
 * @package app\modules\api\resource
 *
 * @property int $id
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
 */
class MailQueueResource extends MailQueue
{
    public string $modelClass = MailQueueResource::class;
    public function fields()
    {
        return [
            'id',
            'ext_id',
            'message',
            'contact_id',
            'user_id',
            'comment',
            'created_at',
            'updated_at',
            'contact' => function ($model) {
                return $model->contact;
            },
            'status' => function ($model) {
                return $model->status->name;
            },
        ];
    }

    public function extraFields()
    {
        return [
            'contact',
            'status',
        ];
    }

    public function getContact()
    {
        return $this->hasOne(ContactResource::class, ['id' => 'contact_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(StatusResource::class, ['id' => 'status_id']);
    }

    public function getContactName()
    {
        return $this->contact->name;
    }

    public function setFieldsFromMessage($message)
    {
        $this->message_id = $message->id;
        $this->to = $message->contact->email;
        $this->subject = 'Ответ на обращение №' . $message->ext_id;
        $this->body = $message->comment;
        $this->from = 'noreply@' . $_SERVER['HTTP_HOST'];
        $this->status = $message->status->name;

        return $this;
    }

    public function isDuplicate()
    {
        $duplicate = MailQueue::findOne([
            'to' => $this->to,
            'subject' => $this->subject,
            'body' => $this->body,
            'status' => $this->status,
        ]);
        if ($duplicate) {
            return true;
        }
        return false;
    }
}