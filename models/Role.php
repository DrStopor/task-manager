<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $level
 * @property string $created_at
 * @property string $updated_at
 * @property bool $is_disabled
 */
class Role extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'role';
    }

    public function rules(): array
    {
        return [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            [['level'], 'integer'],
            [['is_disabled'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название роли',
            'description' => 'Описание',
            'level' => 'Уровень',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'is_disabled' => 'Отключена',
        ];
    }

    public function getUsers(): \yii\db\ActiveQuery
    {
        return $this->hasMany(User::class, ['role_id' => 'id']);
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
        return self::find()->select(['name', 'description'])->indexBy('name')->column();
    }
}