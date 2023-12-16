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
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp(),
        ]);

        $this->batchInsert($this->tableName, ['name'], [
            ['active'],
            ['resolve'],
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
