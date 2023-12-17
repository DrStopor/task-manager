<?php

use yii\db\Migration;

/**
 * Class m231217_085644_error_log
 */
class m231217_085644_error_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('error_log', [
            'id' => $this->primaryKey(),
            'message' => $this->text(),
            'controller' => $this->string(255),
            'action' => $this->string(255),
            'params' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-error_log-created_at', 'error_log', 'created_at');
        $this->createIndex('idx-error_log-controller', 'error_log', 'controller');
        $this->createIndex('idx-error_log-action', 'error_log', 'action');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-error_log-created_at', 'error_log');
        $this->dropIndex('idx-error_log-controller', 'error_log');
        $this->dropIndex('idx-error_log-action', 'error_log');
        $this->dropTable('error_log');
    }
}
