<?php

namespace app\modules\api\resource;

use app\models\Contact;

/**
 * Class ContactResource
 * @package app\modules\api\resource
 *
 * @property MessageResource[] $messages
 */
class ContactResource extends Contact
{
    public string $modelClass = ContactResource::class;

    public function fields()
    {
        return [
            'id',
            'name',
            'email',
        ];
    }

    public function extraFields()
    {
        return [
            'messages',
        ];
    }

    public function getMessages()
    {
        return $this->hasMany(MessageResource::class, ['contact_id' => 'id']);
    }
}