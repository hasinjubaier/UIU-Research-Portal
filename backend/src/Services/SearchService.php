<?php

namespace App\Services;

use PDO;

class SearchService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function searchAll(string $query, int $limit = 5): array
    {
        $queryLike = "%{$query}%";

        // Projects
        $projStmt = $this->db->prepare("
            SELECT id, title, domain, description, 'project' as `type`
            FROM projects
            WHERE (title LIKE ? OR domain LIKE ? OR description LIKE ?) AND deleted_at IS NULL
            LIMIT ?
        ");
        $projStmt->bindValue(1, $queryLike);
        $projStmt->bindValue(2, $queryLike);
        $projStmt->bindValue(3, $queryLike);
        $projStmt->bindValue(4, $limit, PDO::PARAM_INT);
        $projStmt->execute();
        $projects = $projStmt->fetchAll(PDO::FETCH_ASSOC);

        // Resources
        $resStmt = $this->db->prepare("
            SELECT id, title, category, type, author_name, 'resource' as `result_type`
            FROM resources
            WHERE (title LIKE ? OR category LIKE ? OR author_name LIKE ?) AND deleted_at IS NULL
            LIMIT ?
        ");
        $resStmt->bindValue(1, $queryLike);
        $resStmt->bindValue(2, $queryLike);
        $resStmt->bindValue(3, $queryLike);
        $resStmt->bindValue(4, $limit, PDO::PARAM_INT);
        $resStmt->execute();
        $resources = $resStmt->fetchAll(PDO::FETCH_ASSOC);

        // Users / Collaborators
        $userStmt = $this->db->prepare("
            SELECT id, name, department, role, initials, reputation, 'user' as `type`
            FROM users
            WHERE (name LIKE ? OR department LIKE ? OR bio LIKE ?) AND deleted_at IS NULL
            LIMIT ?
        ");
        $userStmt->bindValue(1, $queryLike);
        $userStmt->bindValue(2, $queryLike);
        $userStmt->bindValue(3, $queryLike);
        $userStmt->bindValue(4, $limit, PDO::PARAM_INT);
        $userStmt->execute();
        $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

        // Ideas
        $ideaStmt = $this->db->prepare("
            SELECT id, title, domain, upvotes_count, 'idea' as `type`
            FROM ideas
            WHERE (title LIKE ? OR domain LIKE ? OR description LIKE ?) AND deleted_at IS NULL
            LIMIT ?
        ");
        $ideaStmt->bindValue(1, $queryLike);
        $ideaStmt->bindValue(2, $queryLike);
        $ideaStmt->bindValue(3, $queryLike);
        $ideaStmt->bindValue(4, $limit, PDO::PARAM_INT);
        $ideaStmt->execute();
        $ideas = $ideaStmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'query'     => $query,
            'projects'  => $projects,
            'resources' => $resources,
            'users'     => $users,
            'ideas'     => $ideas,
            'total'     => count($projects) + count($resources) + count($users) + count($ideas)
        ];
    }
}
