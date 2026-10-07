<?php

namespace App\Models;

use PDO;

class Idea extends BaseModel
{
    protected string $table = 'ideas';
    protected array $fillable = [
        'user_id', 'title', 'description', 'domain', 'status',
        'upvotes_count', 'comments_count'
    ];

    public function listWithDetails(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $conditions = ["i.deleted_at IS NULL"];
        $params = [];

        if (!empty($filters['domain'])) {
            $conditions[] = "i.domain LIKE ?";
            $params[] = "%{$filters['domain']}%";
        }

        if (!empty($filters['status'])) {
            $conditions[] = "i.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['query'])) {
            $conditions[] = "(i.title LIKE ? OR i.description LIKE ?)";
            $params[] = "%{$filters['query']}%";
            $params[] = "%{$filters['query']}%";
        }

        $where = implode(' AND ', $conditions);

        $countStmt = $this->connection->prepare("SELECT COUNT(*) FROM ideas i WHERE {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql = "
            SELECT 
                i.*, DATE_FORMAT(i.created_at, '%Y-%m-%d') as `date`,
                u.name as author_name, u.initials as author_initials
            FROM ideas i
            JOIN users u ON i.user_id = u.id
            WHERE {$where}
            ORDER BY i.upvotes_count DESC, i.id DESC
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
        $ideas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($ideas as &$idea) {
            $sStmt = $this->connection->prepare("SELECT skill FROM idea_skills WHERE idea_id = ?");
            $sStmt->execute([$idea['id']]);
            $idea['skills'] = $sStmt->fetchAll(PDO::FETCH_COLUMN);

            $idea['author'] = (int)$idea['user_id'];
            $idea['upvotes'] = (int)$idea['upvotes_count'];
            $idea['comments'] = (int)$idea['comments_count'];
        }

        return [
            'data'       => $ideas,
            'pagination' => [
                'page'     => $page,
                'per_page' => $perPage,
                'total'    => $total,
                'pages'    => ceil($total / max(1, $perPage)),
                'has_more' => $page < ceil($total / max(1, $perPage))
            ]
        ];
    }

    public function toggleUpvote(int $ideaId, int $userId): array
    {
        $stmt = $this->connection->prepare("SELECT id FROM idea_upvotes WHERE idea_id = ? AND user_id = ?");
        $stmt->execute([$ideaId, $userId]);
        $upvoteId = $stmt->fetchColumn();

        if ($upvoteId) {
            $this->connection->prepare("DELETE FROM idea_upvotes WHERE id = ?")->execute([$upvoteId]);
            $this->connection->prepare("UPDATE ideas SET upvotes_count = GREATEST(0, upvotes_count - 1) WHERE id = ?")->execute([$ideaId]);
            $upvoted = false;
        } else {
            $this->connection->prepare("INSERT INTO idea_upvotes (idea_id, user_id) VALUES (?, ?)")->execute([$ideaId, $userId]);
            $this->connection->prepare("UPDATE ideas SET upvotes_count = upvotes_count + 1 WHERE id = ?")->execute([$ideaId]);
            $upvoted = true;
        }

        $cntStmt = $this->connection->prepare("SELECT upvotes_count FROM ideas WHERE id = ?");
        $cntStmt->execute([$ideaId]);
        $count = (int)$cntStmt->fetchColumn();

        return ['upvoted' => $upvoted, 'upvotes' => $count];
    }

    public function syncSkills(int $ideaId, array $skills): void
    {
        $this->connection->prepare("DELETE FROM idea_skills WHERE idea_id = ?")->execute([$ideaId]);
        $stmt = $this->connection->prepare("INSERT INTO idea_skills (idea_id, skill) VALUES (?, ?)");
        foreach ($skills as $skill) {
            $stmt->execute([$ideaId, trim($skill)]);
        }
    }
}
