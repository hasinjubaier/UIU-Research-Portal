<?php
/**
 * Database Migration Runner
 * Usage: php bin/migrate.php [--fresh] [--seed] [--rollback]
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Database\Connection;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$rawPdo = Connection::getRawConnection();
$dbName = $_ENV['DB_NAME'] ?? 'uiu_research_portal';

// 1. Ensure database exists
echo "Ensuring database '{$dbName}' exists...\n";
$rawPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

$pdo = Connection::getInstance();

// 2. Check flags
$isFresh = in_array('--fresh', $argv);
$isSeed = in_array('--seed', $argv);
$isRollback = in_array('--rollback', $argv);

if ($isFresh) {
    echo "Dropping database '{$dbName}' for fresh migration...\n";
    $rawPdo->exec("DROP DATABASE IF EXISTS `{$dbName}`;");
    $rawPdo->exec("CREATE DATABASE `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    Connection::reset();
    $pdo = Connection::getInstance();
}

// 3. Ensure migrations table
$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL UNIQUE,
        batch INT NOT NULL,
        migrated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$stmt = $pdo->query("SELECT migration FROM migrations");
$ranMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

$migrationFiles = glob(__DIR__ . '/../src/Database/migrations/*.php');
sort($migrationFiles);

if ($isRollback) {
    echo "Rollback not implemented for individual batches in simple mode. Use --fresh instead.\n";
    exit(0);
}

// Determine current batch
$stmt = $pdo->query("SELECT MAX(batch) FROM migrations");
$currentBatch = (int)$stmt->fetchColumn() + 1;

$executedCount = 0;
foreach ($migrationFiles as $file) {
    $migrationName = basename($file, '.php');

    if (in_array($migrationName, $ranMigrations) && !$isFresh) {
        continue;
    }

    require_once $file;

    // Convert filename like 001_create_users_table to CreateUsersTable
    $parts = explode('_', $migrationName);
    array_shift($parts); // remove number
    $className = implode('', array_map('ucfirst', $parts));

    if (!class_exists($className)) {
        echo "Error: Class {$className} not found in {$file}\n";
        continue;
    }

    echo "Migrating: {$migrationName}... ";
    $instance = new $className();
    $instance->up($pdo);

    $insertStmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
    $insertStmt->execute([$migrationName, $currentBatch]);

    echo "DONE\n";
    $executedCount++;
}

if ($executedCount === 0) {
    echo "Nothing to migrate. All migrations are up to date.\n";
} else {
    echo "Successfully executed {$executedCount} migrations.\n";
}

// 4. Run seeders if requested
if ($isSeed || $isFresh) {
    echo "\nRunning database seeders...\n";
    require_once __DIR__ . '/seed.php';
}
