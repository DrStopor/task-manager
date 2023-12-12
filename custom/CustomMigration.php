<?php

namespace custom;

use yii\db\Migration;

class CustomMigration extends Migration
{
    public function createPartition($tableName, $partitionName, $partitionValue)
    {
        $this->execute("ALTER TABLE {$tableName} ADD PARTITION (PARTITION {$partitionName} VALUES LESS THAN ({$partitionValue}))");
    }

    public function dropPartition($tableName, $partitionName)
    {
        $this->execute("ALTER TABLE {$tableName} DROP PARTITION {$partitionName}");
    }

    public function createPartitionTable($tableName, $partitionName, $partitionValue)
    {
        $this->execute("CREATE TABLE {$tableName}_{$partitionName} PARTITION OF {$tableName} FOR VALUES IN ({$partitionValue})");
    }

    public function dropPartitionTable($tableName, $partitionName)
    {
        $this->execute("DROP TABLE {$tableName}_{$partitionName}");
    }
}