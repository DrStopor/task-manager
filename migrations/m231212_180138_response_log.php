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

        $this->execute('CREATE OR REPLACE FUNCTION delete_response_log() RETURNS TRIGGER AS $delete_response_log$ BEGIN DELETE FROM response_log WHERE created_at < NOW() - INTERVAL \'3 year\'; RETURN NULL; END; $delete_response_log$ LANGUAGE plpgsql;');

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

        $this->execute('DROP FUNCTION delete_response_log()');
    }
}
