<?php

namespace app\modules\api\resource;

use app\models\Status;
use app\modules\api\helpers\Helper;

class StatusResource extends Status
{
    public string $modelClass = StatusResource::class;

    public function fields(): array
    {
        return [
            'id',
            'name',
        ];
    }

    public function extraFields(): array
    {
        return [
            'messages',
        ];
    }

    public function getMessages(): \yii\db\ActiveQuery
    {
        return $this->hasMany(MessageResource::class, ['status_id' => 'id']);
    }

    /**
     * @param string $name
     * @return StatusResource|null
     */
    public static function getStatusByName(string $name): ?StatusResource
    {
        return self::find()
            ->where(['LOWER(name)' => strtolower(Helper::getClearedString($name))])
            ->limit(1)
            ->one();
    }
}