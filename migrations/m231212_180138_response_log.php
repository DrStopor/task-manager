<?php

use yii\db\Migration;

/**
 * Class m231212_180138_response_log
 */
class m231212_180138_response_log extends Migration
{
    private $tableName = 'response_log';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'response' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'user_id' => $this->integer()->notNull(),
            'message_id' => $this->integer()->notNull(),
        ]);

        $this->execute('CREATE EVENT IF NOT EXISTS `delete_response_log` ON SCHEDULE EVERY 1 DAY STARTS \'2020-01-01 00:00:00\' ON COMPLETION PRESERVE ENABLE DO DELETE FROM response_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 3 YEAR)');

        $this->createIndex(
            'idx-response_log-user_id',
            $this->tableName,
            'user_id'
        );

        $this->createIndex(
            'idx-response_log-message_id',
            $this->tableName,
            'message_id'
        );

        $this->addForeignKey(
            'fk-response_log-user_id',
            $this->tableName,
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-response_log-message_id',
            $this->tableName,
            'message_id',
            'message',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-response_log-created_at',
            $this->tableName,
            'created_at'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-response_log-user_id',
            $this->tableName
        );

        $this->dropForeignKey(
            'fk-response_log-message_id',
            $this->tableName
        );

        $this->dropIndex(
            'idx-response_log-user_id',
            $this->tableName
        );

        $this->dropIndex(
            'idx-response_log-message_id',
            $this->tableName
        );

        $this->dropIndex(
            'idx-response_log-created_at',
            $this->tableName
        );

        $this->dropTable($this->tableName);

        $this->execute('DROP EVENT IF EXISTS `delete_response_log`');
    }
}
