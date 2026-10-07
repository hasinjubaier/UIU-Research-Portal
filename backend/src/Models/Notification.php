<?php

namespace App\Models;

use PDO;

class Notification extends BaseModel
{
    protected string $table = 'notifications';
    protected array $fillable = ['user_id', 'type', 'icon', 'text', 'data', 'is_read', 'read_at'];

    public function getForUser(int $userId, int $limit = 20): array
    {
        $stmt = $this->connection->prepare("
            SELECT id, type, icon, text, data, is_read, created_at,
                   CASE 
                       WHEN TIMESTAMPDIFF(MINUTE, created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, created_at, NOW()), ' minutes ago')
                       WHEN TIMESTAMPDIFF(HOUR, created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, created_at, NOW()), ' hours ago')
                       ELSE CONCAT(TIMESTAMPDIFF(DAY, created_at, NOW()), ' days ago')
                   END as `time`
            FROM notifications
            WHERE user_id = ? AND deleted_at IS NULL
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $notifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($notifs as &$n) {
            $n['read'] = (bool)$n['is_read'];
            if (!empty($n['data'])) {
                $n['data'] = json_decode($n['data'], true);
            }
        }

        return $notifs;
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        $stmt = $this->connection->prepare("
            UPDATE notifications
            SET is_read = 1, read_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$notificationId, $userId]);
    }

    public function markAllAsRead(int $userId): bool
    {
        $stmt = $this->connection->prepare("
            UPDATE notifications
            SET is_read = 1, read_at = NOW()
            WHERE user_id = ? AND is_read = 0
        ");
        return $stmt->execute([$userId]);
    }
}
