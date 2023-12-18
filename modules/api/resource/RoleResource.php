<?php

namespace app\modules\api\resource;

use app\models\Role;

class RoleResource extends Role
{
    public function fields(): array
    {
        return [
            'id',
            'name',
            'description',
            'level',
            'created_at',
            'updated_at',
            'is_disabled',
        ];
    }

    public static function getRoleByName(string $name): ?Role
    {
        return self::findOne(['name' => $name]);
    }

    public static function getRoleById(int $id): ?Role
    {
        return self::findOne(['id' => $id]);
    }

    public static function getRoles(): array
    {
        return self::find()->all();
    }

    public static function getRolesList(): array
    {
        return self::find()->select(['id', 'name'])->asArray()->all();
    }

    public static function getRoleLevelByName(string $name): int
    {
        $role = self::getRoleByName($name);
        return $role->level ?? 0;
    }

    public static function getRoleLevelById(int $id): int
    {
        $role = self::getRoleById($id);
        return $role->level ?? 0;
    }
}