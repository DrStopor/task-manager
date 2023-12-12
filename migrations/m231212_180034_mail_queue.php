<?php

use yii\db\Migration;

/**
 * Class m231212_180034_mail_queue
 */
class m231212_180034_mail_queue extends Migration
{
    private $tableName = 'mail_queue';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
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
