<?php

namespace app\modules\api\resource;

use app\models\Message;

class MessageResource extends Message
{
    public string $modelClass = MessageResource::class;
}