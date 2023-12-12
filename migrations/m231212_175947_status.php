<?php

use yii\db\Migration;

/**
 * Class m231212_175947_status
 */
class m231212_175947_status extends Migration
{
    private $tableName = 'status';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);

        $this->batchInsert($this->tableName, ['name'], [
            ['Active'],
            ['Resolve'],
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
