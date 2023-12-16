<?php

namespace app\modules\api\resource;

use app\models\Message;

/**
 * Class MessageResource
 * @package app\modules\api\resource
 *
 * @property ContactResource $contact
 * @property StatusResource $status
 */
class MessageResource extends Message
{
    public string $modelClass = MessageResource::class;

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

    public function getContactEmail()
    {
        return $this->contact->email;
    }

    public function getMessages()
    {
        return $this->hasMany(MessageResource::class, ['contact_id' => 'id']);
    }
}