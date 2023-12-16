<?php

namespace app\modules\api\resource;

use app\models\Status;

class StatusResource extends Status
{
    public string $modelClass = StatusResource::class;

    public function fields()
    {
        return [
            'id',
            'name',
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
        return $this->hasMany(MessageResource::class, ['status_id' => 'id']);
    }
}