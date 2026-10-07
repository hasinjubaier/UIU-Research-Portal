<?php

class CreateEventsTable
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS events (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT NULL,
                type VARCHAR(50) NOT NULL,
                start_date DATE NOT NULL,
                end_date DATE NULL,
                location VARCHAR(255) NULL,
                prize VARCHAR(100) NULL,
                price VARCHAR(50) DEFAULT 'Free',
                spots INT DEFAULT 100,
                participants_count INT DEFAULT 0,
                status VARCHAR(50) DEFAULT 'open',
                organizer VARCHAR(150) NULL,
                created_by INT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL,
                INDEX idx_type (type),
                INDEX idx_start_date (start_date),
                INDEX idx_status (status),
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS event_tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_id INT NOT NULL,
                tag VARCHAR(50) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_event_id (event_id),
                FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS event_registrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event_id INT NOT NULL,
                user_id INT NOT NULL,
                status VARCHAR(50) DEFAULT 'registered',
                registered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_event_user (event_id, user_id),
                FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("
            DROP TABLE IF EXISTS event_registrations;
            DROP TABLE IF EXISTS event_tags;
            DROP TABLE IF EXISTS events;
        ");
    }
}
