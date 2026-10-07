<?php

namespace App\Models;

use PDO;

class ProjectMember extends BaseModel
{
    protected string $table = 'project_members';
    protected array $fillable = ['project_id', 'user_id', 'role', 'joined_at'];
    protected bool $hasSoftDelete = false;

    public function isMember(int $projectId, int $userId): bool
    {
        $stmt = $this->connection->prepare("
            SELECT 1 FROM project_members
            WHERE project_id = ? AND user_id = ?
        ");
        $stmt->execute([$projectId, $userId]);
        return (bool)$stmt->fetchColumn();
    }

    public function getRole(int $projectId, int $userId): ?string
    {
        $stmt = $this->connection->prepare("
            SELECT role FROM project_members
            WHERE project_id = ? AND user_id = ?
        ");
        $stmt->execute([$projectId, $userId]);
        $role = $stmt->fetchColumn();
        return $role ?: null;
    }

    public function addMember(int $projectId, int $userId, string $role = 'contributor'): bool
    {
        $stmt = $this->connection->prepare("
            INSERT INTO project_members (project_id, user_id, role, joined_at)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE role = VALUES(role)
        ");
        return $stmt->execute([$projectId, $userId, $role]);
    }

    public function removeMember(int $projectId, int $userId): bool
    {
        $stmt = $this->connection->prepare("
            DELETE FROM project_members
            WHERE project_id = ? AND user_id = ?
        ");
        return $stmt->execute([$projectId, $userId]);
    }
}
