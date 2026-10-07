<?php
/**
 * Database Connection (PDO Singleton)
 */

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instance = null;

    /**
     * Get PDO connection instance (singleton)
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                throw new PDOException('Database connection failed: ' . $e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$instance;
    }

    /**
     * Get raw PDO connection without specifying database name
     * Useful for migration scripts to CREATE DATABASE
     */
    public static function getRawConnection(): PDO
    {
        $config = require __DIR__ . '/../../config/database.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['charset']
        );

        return new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            $config['options']
        );
    }

    /**
     * Reset connection instance (useful for testing)
     */
    public static function reset(): void
    {
        self::$instance = null;
    }
}
