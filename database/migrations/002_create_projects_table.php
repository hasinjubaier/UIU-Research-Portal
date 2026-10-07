<?php

class CreateProjectsTable
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS projects (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT NULL,
                domain VARCHAR(100) NULL,
                status ENUM('planning', 'active', 'writing', 'under_review', 'completed') DEFAULT 'planning',
                progress INT DEFAULT 0,
                deadline DATE NULL,
                visibility ENUM('public', 'private') DEFAULT 'public',
                files_count INT DEFAULT 0,
                milestones_count INT DEFAULT 0,
                completed_milestones INT DEFAULT 0,
                created_by INT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL,
                INDEX idx_status (status),
                INDEX idx_created_by (created_by),
                INDEX idx_domain (domain),
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE IF NOT EXISTS project_tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                project_id INT NOT NULL,
                tag VARCHAR(50) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_project_id (project_id),
                FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("
            DROP TABLE IF EXISTS project_tags;
            DROP TABLE IF EXISTS projects;
        ");
    }
}
