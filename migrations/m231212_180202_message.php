<?php

use yii\db\Migration;

/**
 * Class m231212_180202_message
 */
class m231212_180202_message extends Migration
{
    private $tableName = 'message';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'message' => $this->text()->notNull(),
            'status_id' => $this->integer()->notNull(),
            'contact_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'comment' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp(),
        ]);

        $this->execute('CREATE TABLE message_active PARTITION OF message FOR VALUES IN (\'Active\')');
        $this->execute('CREATE TABLE message_resolve PARTITION OF message FOR VALUES IN (\'Resolve\')');

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

        $this->addForeignKey(
            'fk-message-user_id',
            $this->tableName,
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->execute('DROP TABLE message_active');
        $this->execute('DROP TABLE message_resolve');

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

        $this->dropForeignKey(
            'fk-message-user_id',
            $this->tableName
        );

        $this->dropTable($this->tableName);
    }
}
