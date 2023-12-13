<?php

namespace app\custom;

use yii\db\Migration;

class CustomMigration extends Migration
{
    public null|string $dbType;

    public function init(): void
    {
        parent::init();
        $this->dbType = $this->db->getDriverName();
    }

    /**
     * @param string $tableName
     * @param string $partitionName
     * @param mixed $partitionValue
     * @return void
     */
    public function createPartitionTable(string $tableName, string $partitionName, mixed $partitionValue): void
    {
        $this->execute("CREATE TABLE {$tableName}_{$partitionName} PARTITION OF {$tableName} FOR VALUES IN ({$partitionValue})");
    }

    /**
     * @param string $tableName
     * @param string $partitionName
     * @return void
     */
    public function dropPartitionTable(string $tableName, string $partitionName): void
    {
        $this->execute("DROP TABLE {$tableName}_{$partitionName}");
    }
}