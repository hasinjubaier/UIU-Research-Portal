<?php

class CreateTasksTable
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS tasks (
                id INT AUTO_INCREMENT PRIMARY KEY,
                project_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT NULL,
                status ENUM('backlog', 'todo', 'in_progress', 'review', 'done') DEFAULT 'todo',
                priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
                assignee_id INT NULL,
                due_date DATE NULL,
                created_by INT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at DATETIME NULL,
                INDEX idx_project_id (project_id),
                INDEX idx_assignee_id (assignee_id),
                INDEX idx_status (status),
                FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
                FOREIGN KEY (assignee_id) REFERENCES users(id) ON DELETE SET NULL,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down(PDO $pdo): void
    {
        $pdo->exec("
            DROP TABLE IF EXISTS tasks;
        ");
    }
}
