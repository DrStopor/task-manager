<?php

use app\custom\CustomMigration;

/**
 * Class m231212_180202_message
 */
class m231212_180202_message extends CustomMigration
{
    private $tableName = 'message';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'ext_id' => $this->string()->notNull(),
            'message' => $this->text()->notNull(),
            'status_id' => $this->integer()->notNull(),
            'contact_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'comment' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp(),
        ], 'PARTITION BY LIST (status_id)');

        $this->createPartitionTable($this->tableName, 'active', '1');
        $this->createPartitionTable($this->tableName, 'resolve', '2');

        $this->createIndex(
            'idx-message-ext_id',
            $this->tableName,
            'ext_id'
        );

        $this->createIndex(
            'idx-message-status_id',
            $this->tableName,
            'status_id'
        );

        $this->createIndex(
            'idx-message-contact_id',
            $this->tableName,
            'contact_id',
        );

        $this->createIndex(
            'idx-message-user_id',
            $this->tableName,
            'user_id',
        );

        $this->addForeignKey(
            'fk-message-status_id',
            $this->tableName,
            'status_id',
            'status',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-message-contact_id',
            $this->tableName,
            'contact_id',
            'contact',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-message-status_id',
            $this->tableName
        );

        $this->dropIndex(
            'idx-message-contact_id',
            $this->tableName
        );

        $this->dropIndex(
            'idx-message-user_id',
            $this->tableName
        );

        $this->dropForeignKey(
            'fk-message-status_id',
            $this->tableName
        );

        $this->dropForeignKey(
            'fk-message-contact_id',
            $this->tableName
        );

        $this->dropPartitionTable($this->tableName, 'active');
        $this->dropPartitionTable($this->tableName, 'resolve');

        $this->dropTable($this->tableName);
    }
}
