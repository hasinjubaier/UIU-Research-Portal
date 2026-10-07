<?php
/**
 * Standalone Seeder Runner
 * Usage: php bin/seed.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Database\Connection;
use App\Database\Seeds\DatabaseSeeder;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$pdo = Connection::getInstance();

echo "Running Database Seeder...\n";
$seeder = new DatabaseSeeder($pdo);
$seeder->run();
echo "Database successfully seeded with demo data!\n";
