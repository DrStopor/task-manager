<?php

use yii\db\Migration;

/**
 * Class m231213_185803_add_fk_message
 */
class m231213_185803_add_fk_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-response_log-message_id',
            'response_log',
            'message_id',
            'message',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-mail_log-message_id',
            'mail_log',
            'message_id',
            'message',
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
            'fk-response_log-message_id',
            'response_log'
        );

        $this->dropForeignKey(
            'fk-mail_log-message_id',
            'mail_log'
        );
    }
}
