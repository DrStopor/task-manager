<?php

use yii\db\Migration;

/**
 * Class m231218_183049_role
 */
class m231218_183049_role extends Migration
{
    private string $tableName = 'role';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->CreateTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique(),
            'description' => $this->string(255)->notNull(),
            'level' => $this->integer()->notNull()->defaultValue(0)->comment('0 - public, 1 - moderator, 2 - admin, -1 - disabled'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->dateTime(),
            'is_disabled' => $this->boolean()->notNull()->defaultValue(false),
        ]);

        $this->batchInsert($this->tableName, ['name', 'description', 'level'], [
            ['public', 'public user', 0],
            ['moderator', 'moderator', 1],
            ['admin', 'admin', 2],
            ['disabled', 'disabled', -1],
        ]);

        $this->addColumn('user', 'role_id', $this->integer()->notNull()->defaultValue(0));

        $this->addForeignKey(
            'fk_user_role_id',
            'user',
            'role_id',
            'role',
            'id',
            'CASCADE'
        );

        $this->update('user', ['role_id' => 1], ['name' => 'test2']);
        $this->update('user', ['role_id' => 2], ['name' => 'test1']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_role_id', 'user');
        $this->dropColumn('user', 'role_id');
        $this->dropTable($this->tableName);
    }
}
