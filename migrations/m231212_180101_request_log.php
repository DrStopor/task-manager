<?php

use yii\db\Migration;

/**
 * Class m231212_180101_request_log
 */
class m231212_180101_request_log extends Migration
{
    private $tableName = 'request_log';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'ip' => $this->string(255)->notNull(),
            'method' => $this->string(255)->notNull(),
            'action' => $this->string(255)->notNull(),
            'params' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->execute("CREATE EVENT IF NOT EXISTS `delete_request_log` ON SCHEDULE EVERY 1 DAY STARTS '2020-01-01 00:00:00' ON COMPLETION PRESERVE ENABLE DO DELETE FROM request_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 3 YEAR)");

        $this->createIndex(
            'idx-request_log-ip',
            $this->tableName,
            'ip'
        );

        $this->createIndex(
            'idx-request_log-method',
            $this->tableName,
            'method'
        );

        $this->createIndex(
            'idx-request_log-action',
            $this->tableName,
            'action'
        );

        $this->createIndex(
            'idx-request_log-created_at',
            $this->tableName,
            'created_at'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);

        $this->execute('DROP EVENT IF EXISTS `delete_request_log`');
    }
}
