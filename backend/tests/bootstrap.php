<?php
/**
 * PHPUnit Test Bootstrap
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Database\Connection;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Ensure test connection is working
try {
    $pdo = Connection::getInstance();
} catch (\Throwable $e) {
    echo "Warning: Test database not reachable: " . $e->getMessage() . "\n";
}

// Autoload Tests namespace classes
spl_autoload_register(function (string $class) {
    if (strpos($class, 'Tests\\') === 0) {
        $relativePath = str_replace('\\', '/', substr($class, 6)) . '.php';
        $fullPath = __DIR__ . '/' . $relativePath;
        if (file_exists($fullPath)) {
            require_once $fullPath;
        }
    }
});

// Load PHPUnit compatibility shim if PHPUnit is not present in vendor
if (!class_exists('PHPUnit\Framework\TestCase')) {
    require_once __DIR__ . '/PHPUnitShim.php';
}
