<?php

class CreateResourcesTable
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS resources (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT NULL,
                type VARCHAR(50) NOT NULL,
                category VARCHAR(100) NULL,
                user_id INT NULL,
                author_name VARCHAR(150) NULL,
                file_path VARCHAR(255) NULL,
                file_size VARCHAR(50) NULL,
                downloads_count INT DEFAULT 0,
                rating DECIMAL(3,2) DEFAULT 0.00,
                license VARCHAR(50) DEFAULT 'MIT',
                version VARCHAR(20) DEFAULT 'v1.0',
                stars_count INT DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL,
                INDEX idx_type (type),
                INDEX idx_category (category),
                INDEX idx_user_id (user_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS resource_tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                resource_id INT NOT NULL,
                tag VARCHAR(50) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_resource_id (resource_id),
                FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS resource_downloads (
                id INT AUTO_INCREMENT PRIMARY KEY,
                resource_id INT NOT NULL,
                user_id INT NULL,
                downloaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_resource_id (resource_id),
                FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("
            DROP TABLE IF EXISTS resource_downloads;
            DROP TABLE IF EXISTS resource_tags;
            DROP TABLE IF EXISTS resources;
        ");
    }
}
