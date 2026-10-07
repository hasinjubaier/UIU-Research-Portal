<?php

namespace App\Models;

use PDO;

class Contribution extends BaseModel
{
    protected string $table = 'contributions';
    protected array $fillable = ['project_id', 'user_id', 'edits', 'uploads', 'tasks', 'comments', 'score'];
    protected bool $hasSoftDelete = false;

    public function getByProject(int $projectId): array
    {
        $stmt = $this->connection->prepare("
            SELECT 
                c.user_id as studentId, c.edits, c.uploads, c.tasks, c.comments, c.score,
                u.name as studentName, u.initials as studentInitials
            FROM contributions c
            JOIN users u ON c.user_id = u.id
            WHERE c.project_id = ?
            ORDER BY c.score DESC
        ");
        $stmt->execute([$projectId]);
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($members as &$m) {
            $m['studentId'] = (int)$m['studentId'];
            $m['edits'] = (int)$m['edits'];
            $m['uploads'] = (int)$m['uploads'];
            $m['tasks'] = (int)$m['tasks'];
            $m['comments'] = (int)$m['comments'];
            $m['score'] = (int)$m['score'];
        }

        return [
            'projectId' => $projectId,
            'members'   => $members
        ];
    }
}
