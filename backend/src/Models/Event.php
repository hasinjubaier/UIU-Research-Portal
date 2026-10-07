<?php

namespace App\Models;

use PDO;

class Event extends BaseModel
{
    protected string $table = 'events';
    protected array $fillable = [
        'title', 'description', 'type', 'start_date', 'end_date',
        'location', 'prize', 'price', 'spots', 'participants_count',
        'status', 'organizer', 'created_by'
    ];

    public function listWithDetails(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $conditions = ["e.deleted_at IS NULL"];
        $params = [];

        if (!empty($filters['type'])) {
            $conditions[] = "e.type = ?";
            $params[] = $filters['type'];
        }

        if (!empty($filters['status'])) {
            $conditions[] = "e.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['query'])) {
            $conditions[] = "(e.title LIKE ? OR e.description LIKE ? OR e.organizer LIKE ?)";
            $params[] = "%{$filters['query']}%";
            $params[] = "%{$filters['query']}%";
            $params[] = "%{$filters['query']}%";
        }

        $where = implode(' AND ', $conditions);

        $countStmt = $this->connection->prepare("SELECT COUNT(*) FROM events e WHERE {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql = "
            SELECT e.*, DATE_FORMAT(e.start_date, '%Y-%m-%d') as `date`, DATE_FORMAT(e.end_date, '%Y-%m-%d') as `endDate`
            FROM events e
            WHERE {$where}
            ORDER BY e.start_date ASC
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
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($events as &$e) {
            $tStmt = $this->connection->prepare("SELECT tag FROM event_tags WHERE event_id = ?");
            $tStmt->execute([$e['id']]);
            $e['tags'] = $tStmt->fetchAll(PDO::FETCH_COLUMN);

            $e['participants'] = (int)$e['participants_count'];
            $e['spots'] = (int)$e['spots'];
        }

        return [
            'data'       => $events,
            'pagination' => [
                'page'     => $page,
                'per_page' => $perPage,
                'total'    => $total,
                'pages'    => ceil($total / max(1, $perPage)),
                'has_more' => $page < ceil($total / max(1, $perPage))
            ]
        ];
    }

    public function registerUser(int $eventId, int $userId): bool
    {
        $stmt = $this->connection->prepare("
            INSERT INTO event_registrations (event_id, user_id, registered_at)
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE registered_at = VALUES(registered_at)
        ");
        $success = $stmt->execute([$eventId, $userId]);

        if ($success) {
            $this->connection->prepare("
                UPDATE events
                SET participants_count = participants_count + 1
                WHERE id = ?
            ")->execute([$eventId]);
        }

        return $success;
    }

    public function syncTags(int $eventId, array $tags): void
    {
        $this->connection->prepare("DELETE FROM event_tags WHERE event_id = ?")->execute([$eventId]);
        $stmt = $this->connection->prepare("INSERT INTO event_tags (event_id, tag) VALUES (?, ?)");
        foreach ($tags as $tag) {
            $stmt->execute([$eventId, trim($tag)]);
        }
    }
}
