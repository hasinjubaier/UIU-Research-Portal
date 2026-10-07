<?php

namespace App\Models;

use PDO;

class Resource extends BaseModel
{
    protected string $table = 'resources';
    protected array $fillable = [
        'title', 'description', 'type', 'category', 'user_id',
        'author_name', 'file_path', 'file_size', 'downloads_count',
        'rating', 'license', 'version', 'stars_count'
    ];

    public function listWithDetails(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $conditions = ["r.deleted_at IS NULL"];
        $params = [];

        if (!empty($filters['type'])) {
            $conditions[] = "r.type = ?";
            $params[] = $filters['type'];
        }

        if (!empty($filters['category'])) {
            $conditions[] = "r.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['is_dataset'])) {
            $conditions[] = "r.type IN ('Dataset', 'Code', 'Experiment')";
        }

        if (!empty($filters['query'])) {
            $conditions[] = "(r.title LIKE ? OR r.description LIKE ? OR r.author_name LIKE ?)";
            $params[] = "%{$filters['query']}%";
            $params[] = "%{$filters['query']}%";
            $params[] = "%{$filters['query']}%";
        }

        $where = implode(' AND ', $conditions);

        $countStmt = $this->connection->prepare("SELECT COUNT(*) FROM resources r WHERE {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql = "
            SELECT r.*, DATE_FORMAT(r.created_at, '%Y-%m-%d') as `date`
            FROM resources r
            WHERE {$where}
            ORDER BY r.id DESC
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
        $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resources as &$r) {
            $tStmt = $this->connection->prepare("SELECT tag FROM resource_tags WHERE resource_id = ?");
            $tStmt->execute([$r['id']]);
            $r['tags'] = $tStmt->fetchAll(PDO::FETCH_COLUMN);

            $r['downloads'] = (int)$r['downloads_count'];
            $r['stars'] = (int)$r['stars_count'];
            $r['rating'] = (float)$r['rating'];
            $r['author'] = $r['author_name'];
            $r['size'] = $r['file_size'];
        }

        return [
            'data'       => $resources,
            'pagination' => [
                'page'     => $page,
                'per_page' => $perPage,
                'total'    => $total,
                'pages'    => ceil($total / max(1, $perPage)),
                'has_more' => $page < ceil($total / max(1, $perPage))
            ]
        ];
    }

    public function incrementDownloads(int $resourceId, ?int $userId = null): void
    {
        $this->connection->prepare("
            UPDATE resources
            SET downloads_count = downloads_count + 1
            WHERE id = ?
        ")->execute([$resourceId]);

        if ($userId) {
            $this->connection->prepare("
                INSERT INTO resource_downloads (resource_id, user_id, downloaded_at)
                VALUES (?, ?, NOW())
            ")->execute([$resourceId, $userId]);
        }
    }

    public function syncTags(int $resourceId, array $tags): void
    {
        $this->connection->prepare("DELETE FROM resource_tags WHERE resource_id = ?")->execute([$resourceId]);
        $stmt = $this->connection->prepare("INSERT INTO resource_tags (resource_id, tag) VALUES (?, ?)");
        foreach ($tags as $tag) {
            $stmt->execute([$resourceId, trim($tag)]);
        }
    }
}
