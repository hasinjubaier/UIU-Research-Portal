<?php

class CreateUsersTable
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                email VARCHAR(191) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                avatar VARCHAR(255) NULL,
                initials VARCHAR(10) NULL,
                department VARCHAR(100) NULL,
                academic_year VARCHAR(50) NULL,
                role VARCHAR(50) DEFAULT 'Student',
                bio TEXT NULL,
                reputation INT DEFAULT 0,
                status ENUM('online', 'offline', 'away') DEFAULT 'offline',
                email_verified_at DATETIME NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL,
                INDEX idx_email (email),
                INDEX idx_reputation (reputation),
                INDEX idx_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS user_skills (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                skill VARCHAR(100) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user_id (user_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS user_interests (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                interest VARCHAR(100) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user_id (user_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS user_looking_for (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                looking_for VARCHAR(100) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user_id (user_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("
            DROP TABLE IF EXISTS user_looking_for;
            DROP TABLE IF EXISTS user_interests;
            DROP TABLE IF EXISTS user_skills;
            DROP TABLE IF EXISTS users;
        ");
    }
}
