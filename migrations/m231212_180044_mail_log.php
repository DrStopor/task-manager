<?php

use yii\db\Migration;

/**
 * Class m231212_180044_mail_log
 */
class m231212_180044_mail_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('mail_log', [
            'id' => $this->primaryKey(),
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
            'message_id' => $this->integer()->notNull(),
            'error' => $this->text(),
            'is_sent' => $this->boolean()->notNull()->defaultValue(false),
        ]);

        $this->execute('CREATE TABLE mail_log_not_send PARTITION OF mail_log FOR VALUES IN (false)');
        $this->execute('CREATE TABLE mail_log_send PARTITION OF mail_log FOR VALUES IN (true)');

        $this->createIndex(
            'idx-mail_log-message_id',
            'mail_log',
            'message_id'
        );

        $this->addForeignKey(
            'fk-mail_log-message_id',
            'mail_log',
            'message_id',
            'message',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-mail_log-status',
            'mail_log',
            'status'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-mail_log-message_id',
            'mail_log'
        );

        $this->dropIndex(
            'idx-mail_log-message_id',
            'mail_log'
        );

        $this->dropIndex(
            'idx-mail_log-status',
            'mail_log'
        );

        $this->dropTable('mail_log');
    }
}
