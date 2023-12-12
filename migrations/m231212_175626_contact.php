<?php

use yii\db\Migration;

/**
 * Class m231212_175626_contact
 */
class m231212_175626_contact extends Migration
{
    private $tableName = 'contact';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull()->unique(),
        ]);

        $this->createIndex(
            'idx-contact-email',
            $this->tableName,
            'email'
        );

        $this->createIndex(
            'idx-contact-name',
            $this->tableName,
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-contact-email',
            $this->tableName
        );

        $this->dropIndex(
            'idx-contact-name',
            $this->tableName
        );

        $this->dropTable($this->tableName);
    }
}
