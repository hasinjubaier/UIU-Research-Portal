<?php

namespace App\Models;

use PDO;

class Task extends BaseModel
{
    protected string $table = 'tasks';
    protected array $fillable = [
        'project_id', 'title', 'description', 'status', 'priority',
        'assignee_id', 'due_date', 'created_by'
    ];

    public function getKanbanByProject(int $projectId): array
    {
        $stmt = $this->connection->prepare("
            SELECT t.*, u.name as assignee_name, u.initials as assignee_initials, u.avatar as assignee_avatar
            FROM tasks t
            LEFT JOIN users u ON t.assignee_id = u.id
            WHERE t.project_id = ? AND t.deleted_at IS NULL
            ORDER BY t.id ASC
        ");
        $stmt->execute([$projectId]);
        $allTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $kanban = [
            'backlog'    => [],
            'todo'       => [],
            'inProgress' => [],
            'review'     => [],
            'done'       => []
        ];

        foreach ($allTasks as $t) {
            $formatted = [
                'id'       => (int)$t['id'],
                'title'    => $t['title'],
                'priority' => $t['priority'],
                'assignee' => $t['assignee_id'] ? (int)$t['assignee_id'] : null,
                'assigneeDetails' => $t['assignee_id'] ? [
                    'id'       => (int)$t['assignee_id'],
                    'name'     => $t['assignee_name'],
                    'initials' => $t['assignee_initials'],
                ] : null,
                'due'      => $t['due_date'] ? date('M j', strtotime($t['due_date'])) : null,
                'dueDate'  => $t['due_date'],
                'status'   => $t['status']
            ];

            $statusKey = match($t['status']) {
                'in_progress' => 'inProgress',
                'backlog'     => 'backlog',
                'review'      => 'review',
                'done'        => 'done',
                default       => 'todo'
            };

            $kanban[$statusKey][] = $formatted;
        }

        return $kanban;
    }
}
