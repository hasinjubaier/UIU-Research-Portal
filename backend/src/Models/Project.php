<?php

namespace App\Models;

use PDO;

class Project extends BaseModel
{
    protected string $table = 'projects';
    protected array $fillable = [
        'title', 'description', 'domain', 'status', 'progress',
        'deadline', 'visibility', 'files_count', 'milestones_count',
        'completed_milestones', 'created_by'
    ];

    public function findWithDetails(int $id): ?array
    {
        $project = $this->findById($id);
        if (!$project) {
            return null;
        }

        // Project tags
        $stmt = $this->connection->prepare("SELECT tag FROM project_tags WHERE project_id = ?");
        $stmt->execute([$id]);
        $project['tags'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Members with user profile
        $stmt = $this->connection->prepare("
            SELECT u.id, u.name, u.email, u.avatar, u.initials, u.department, pm.role, pm.joined_at
            FROM project_members pm
            JOIN users u ON pm.user_id = u.id
            WHERE pm.project_id = ? AND u.deleted_at IS NULL
        ");
        $stmt->execute([$id]);
        $project['membersList'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $project['members'] = array_column($project['membersList'], 'id');

        // Task stats
        $stmt = $this->connection->prepare("
            SELECT 
                COUNT(CASE WHEN status = 'todo' THEN 1 END) as todo_count,
                COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as in_progress_count,
                COUNT(CASE WHEN status = 'done' THEN 1 END) as done_count
            FROM tasks
            WHERE project_id = ? AND deleted_at IS NULL
        ");
        $stmt->execute([$id]);
        $taskCounts = $stmt->fetch(PDO::FETCH_ASSOC);
        $project['tasks'] = [
            'todo'       => (int)($taskCounts['todo_count'] ?? 0),
            'inProgress' => (int)($taskCounts['in_progress_count'] ?? 0),
            'done'       => (int)($taskCounts['done_count'] ?? 0),
        ];

        return $project;
    }

    public function listWithDetails(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $conditions = ["p.deleted_at IS NULL"];
        $params = [];

        if (!empty($filters['status'])) {
            $conditions[] = "p.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['visibility'])) {
            $conditions[] = "p.visibility = ?";
            $params[] = $filters['visibility'];
        }

        if (!empty($filters['domain'])) {
            $conditions[] = "p.domain LIKE ?";
            $params[] = "%{$filters['domain']}%";
        }

        if (!empty($filters['user_id'])) {
            $conditions[] = "EXISTS (SELECT 1 FROM project_members pm WHERE pm.project_id = p.id AND pm.user_id = ?)";
            $params[] = $filters['user_id'];
        }

        if (!empty($filters['query'])) {
            $conditions[] = "(p.title LIKE ? OR p.description LIKE ?)";
            $params[] = "%{$filters['query']}%";
            $params[] = "%{$filters['query']}%";
        }

        $where = implode(' AND ', $conditions);

        $countStmt = $this->connection->prepare("SELECT COUNT(*) FROM projects p WHERE {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql = "
            SELECT p.*
            FROM projects p
            WHERE {$where}
            ORDER BY p.id DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->connection->prepare($sql);
        $idx = 1;
        foreach ($params as $p) {
            $stmt->bindValue($idx++, $p);
        }
        $stmt->bindValue($idx++, $perPage, PDO::PARAM_INT);
        $stmt->bindValue($idx, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($projects as &$proj) {
            $tStmt = $this->connection->prepare("SELECT tag FROM project_tags WHERE project_id = ?");
            $tStmt->execute([$proj['id']]);
            $proj['tags'] = $tStmt->fetchAll(PDO::FETCH_COLUMN);

            $mStmt = $this->connection->prepare("SELECT user_id FROM project_members WHERE project_id = ?");
            $mStmt->execute([$proj['id']]);
            $proj['members'] = array_map('intval', $mStmt->fetchAll(PDO::FETCH_COLUMN));

            $taskStmt = $this->connection->prepare("
                SELECT 
                    COUNT(CASE WHEN status = 'todo' THEN 1 END) as todo_count,
                    COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as in_progress_count,
                    COUNT(CASE WHEN status = 'done' THEN 1 END) as done_count
                FROM tasks
                WHERE project_id = ? AND deleted_at IS NULL
            ");
            $taskStmt->execute([$proj['id']]);
            $tc = $taskStmt->fetch(PDO::FETCH_ASSOC);
            $proj['tasks'] = [
                'todo'       => (int)($tc['todo_count'] ?? 0),
                'inProgress' => (int)($tc['in_progress_count'] ?? 0),
                'done'       => (int)($tc['done_count'] ?? 0),
            ];
        }

        return [
            'data'       => $projects,
            'pagination' => [
                'page'     => $page,
                'per_page' => $perPage,
                'total'    => $total,
                'pages'    => ceil($total / max(1, $perPage)),
                'has_more' => $page < ceil($total / max(1, $perPage))
            ]
        ];
    }

    public function syncTags(int $projectId, array $tags): void
    {
        $this->connection->prepare("DELETE FROM project_tags WHERE project_id = ?")->execute([$projectId]);
        $stmt = $this->connection->prepare("INSERT INTO project_tags (project_id, tag) VALUES (?, ?)");
        foreach ($tags as $tag) {
            $stmt->execute([$projectId, trim($tag)]);
        }
    }
}
