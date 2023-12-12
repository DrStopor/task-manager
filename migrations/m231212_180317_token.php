<?php

use yii\db\Migration;

/**
 * Class m231212_180317_token
 */
class m231212_180317_token extends Migration
{
    private $tableName = 'token';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'token' => $this->string(512)->notNull()->unique(),
        ]);

        $this->batchInsert($this->tableName, ['token'], [
            [hash('sha256', rand(100000, 999999))],
            [hash('sha256', rand(100000, 999999))],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
