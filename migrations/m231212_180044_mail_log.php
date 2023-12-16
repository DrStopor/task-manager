<?php

use app\custom\CustomMigration;

/**
 * Class m231212_180044_mail_log
 */
class m231212_180044_mail_log extends CustomMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('mail_log', [
            'message_id' => $this->integer()->notNull(),
            'from' => $this->string(255)->notNull(),
            'to' => $this->string(255)->notNull(),
            'cc' => $this->string(255),
            'bcc' => $this->string(255),
            'subject' => $this->string(255)->notNull(),
            'body' => $this->text()->notNull(),
            'attachment' => $this->string(255),
            'status' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp(),
            'error' => $this->text(),
            'is_sent' => $this->boolean()->notNull()->defaultValue(false),
        ], 'PARTITION BY LIST (is_sent)');

        $this->createPartitionTable('mail_log', 'not_send', 'false');
        $this->createPartitionTable('mail_log', 'send', 'true');

        $this->createIndex(
            'idx-mail_log-status',
            'mail_log',
            'status'
        );

        $this->createIndex(
            'idx-mail_log-message_id',
            'mail_log',
            'message_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-mail_log-message_id',
            'mail_log'
        );

        $this->dropIndex(
            'idx-mail_log-status',
            'mail_log'
        );

        $this->dropPartitionTable('mail_log', 'not_send');
        $this->dropPartitionTable('mail_log', 'send');

        $this->dropTable('mail_log');
    }
}
