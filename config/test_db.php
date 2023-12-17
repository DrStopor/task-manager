<?php
$db = require __DIR__ . '/db.php';

$testDbHost = getenv('TEST_DB_HOST');
$testDbName = getenv('TEST_DB_NAME');

// test database! Important not to run tests on production or development databases
$db['dsn'] = "pgsql:host=$testDbHost;dbname=$testDbName";

return $db;
