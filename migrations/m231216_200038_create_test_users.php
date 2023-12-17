<?php

use yii\db\Migration;

/**
 * Class m231216_200038_create_test_users
 */
class m231216_200038_create_test_users extends Migration
{
    private string $tableName = 'user';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert($this->tableName, ['name', 'description', 'token_id'], [
            ['test1', 'moderator', 1],
            ['test2', 'public user', 2],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
