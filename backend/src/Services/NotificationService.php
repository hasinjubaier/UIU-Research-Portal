<?php

namespace App\Services;

use App\Models\Notification;
use App\Utils\Logger;
use PDO;

class NotificationService
{
    private PDO $db;
    private Notification $notifModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->notifModel = new Notification($db);
    }

    public function getForUser(int $userId, int $limit = 20): array
    {
        return $this->notifModel->getForUser($userId, $limit);
    }

    public function notify(int $userId, string $type, string $text, string $icon = 'system', ?array $data = null): int
    {
        $id = $this->notifModel->create([
            'user_id' => $userId,
            'type'    => $type,
            'text'    => $text,
            'icon'    => $icon,
            'data'    => $data ? json_encode($data) : null,
            'is_read' => 0,
        ]);

        Logger::info("Notification sent to User {$userId}: {$text}");
        return (int)$id;
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        return $this->notifModel->markAsRead($notificationId, $userId);
    }

    public function markAllAsRead(int $userId): bool
    {
        return $this->notifModel->markAllAsRead($userId);
    }
}
