<?php

namespace App\Database\Seeds;

use PDO;

class TaskSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $tasks = [
            ['id' => 1, 'project_id' => 1, 'title' => 'Collect chest X-ray dataset from NIH', 'priority' => 'high', 'status' => 'todo', 'assignee_id' => 7, 'due_date' => '2025-05-20'],
            ['id' => 2, 'project_id' => 1, 'title' => 'Write preprocessing pipeline docs', 'priority' => 'medium', 'status' => 'todo', 'assignee_id' => 1, 'due_date' => '2025-05-25'],
            ['id' => 3, 'project_id' => 1, 'title' => 'Research augmentation techniques', 'priority' => 'low', 'status' => 'todo', 'assignee_id' => 5, 'due_date' => '2025-05-28'],
            ['id' => 4, 'project_id' => 1, 'title' => 'Train CNN baseline model', 'priority' => 'high', 'status' => 'in_progress', 'assignee_id' => 5, 'due_date' => '2025-05-18'],
            ['id' => 5, 'project_id' => 1, 'title' => 'Implement DICOM reader module', 'priority' => 'medium', 'status' => 'in_progress', 'assignee_id' => 1, 'due_date' => '2025-05-22'],
            ['id' => 6, 'project_id' => 1, 'title' => 'Project setup and repository init', 'priority' => 'medium', 'status' => 'done', 'assignee_id' => 1, 'due_date' => '2025-04-10'],
            ['id' => 7, 'project_id' => 1, 'title' => 'Literature review — 20 papers', 'priority' => 'high', 'status' => 'done', 'assignee_id' => 7, 'due_date' => '2025-04-20'],
            ['id' => 8, 'project_id' => 1, 'title' => 'Define model architecture', 'priority' => 'high', 'status' => 'done', 'assignee_id' => 5, 'due_date' => '2025-04-28'],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO tasks (id, project_id, title, priority, status, assignee_id, due_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE title=VALUES(title), status=VALUES(status)
        ");

        foreach ($tasks as $t) {
            $stmt->execute([$t['id'], $t['project_id'], $t['title'], $t['priority'], $t['status'], $t['assignee_id'], $t['due_date']]);
        }
    }
}
