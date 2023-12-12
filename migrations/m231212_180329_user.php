<?php

use yii\db\Migration;

/**
 * Class m231212_180329_user
 */
class m231212_180329_user extends Migration
{
    private $tableName = 'user';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(512),
            'token_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            ]);

        $this->createIndex(
            'idx-user-token_id',
            $this->tableName,
            'token_id'
        );

        $this->addForeignKey(
            'fk-user-token_id',
            $this->tableName,
            'token_id',
            'token',
            'token_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user-token_id',
            $this->tableName
        );

        $this->dropIndex(
            'idx-user-token_id',
            $this->tableName
        );

        $this->dropTable($this->tableName);
    }
}
