<?php

use yii\db\Migration;

/**
 * Class m231213_182610_add_fk_user
 */
class m231213_182610_add_fk_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-message-user_id',
            'message',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-response_log-user_id',
            'response_log',
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
        $this->dropForeignKey(
            'fk-message-user_id',
            'message'
        );

        $this->dropForeignKey(
            'fk-response_log-user_id',
            'response_log'
        );
    }
}
