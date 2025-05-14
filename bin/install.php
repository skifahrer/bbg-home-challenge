#!/usr/bin/env php
<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database\MariaDatabaseConnection;

// sql file
$sqlFile = __DIR__ . '/../migrations/init.sql';

if (!file_exists($sqlFile)) {
    echo "❌ SQL file not found: $sqlFile\n";
    exit(1);
}

try {
    $connection = new MariaDatabaseConnection();
    $dbManager = $connection->getManager();
    echo "✅ Successfully connected.\n";
} catch (PDOException $e) {
    echo "❌ Error while connecting: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    // load content
    $sql = file_get_contents($sqlFile);

    // divide commands if needed
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $stmt) {
        if ($stmt !== '') {
            $dbManager->exec($stmt);
        }
    }

    echo "✅ SQL file successfully executed.\n";
} catch (PDOException $e) {
    echo "❌ Error while executing sql: " . $e->getMessage() . "\n";
    exit(1);
}

