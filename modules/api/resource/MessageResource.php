<?php

namespace app\modules\api\resource;

use app\models\Message;
use app\models\Status;
use app\modules\api\helpers\StatusEnum;

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
        return $this->hasMany(__CLASS__, ['contact_id' => 'id']);
    }

    public static function getAllMessagesByStatusName(string $statusName)
    {
        return self::find()
            ->where(['LOWER(status.name)' => strtolower($statusName)])
            ->joinWith('status')
            ->with('contact')
            ->all();
    }

    public static function getAllMessages()
    {
        return self::find()
            ->with('contact')
            ->with('status')
            ->all();
    }

    // get all messages in Active status
    public static function getAllActiveMessages()
    {
        return self::find()
            ->where(['status_id' => StatusEnum::Active])
            ->with('contact')
            ->with('status')
            ->all();
    }
}