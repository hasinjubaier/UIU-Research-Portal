<?php

class CreateNotificationsTable
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS notifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                type VARCHAR(50) NOT NULL,
                icon VARCHAR(50) DEFAULT 'system',
                text TEXT NOT NULL,
                data JSON NULL,
                is_read TINYINT(1) DEFAULT 0,
                read_at DATETIME NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL,
                INDEX idx_user_id (user_id),
                INDEX idx_is_read (is_read),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("
            DROP TABLE IF EXISTS notifications;
        ");
    }
}
