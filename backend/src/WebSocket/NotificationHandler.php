<?php

namespace App\WebSocket;

use App\Utils\Logger;
use SplObjectStorage;

class NotificationHandler
{
    private SplObjectStorage $clients;
    private array $userMap = []; // [userId => [connId => conn]]

    public function __construct()
    {
        $this->clients = new SplObjectStorage();
    }

    public function registerUser(int $userId, $conn): void
    {
        $this->userMap[$userId][$conn->resourceId] = $conn;
        Logger::info("User {$userId} registered for live WebSocket notifications");
    }

    public function unregisterUser(int $userId, $conn): void
    {
        unset($this->userMap[$userId][$conn->resourceId]);
    }

    /**
     * Push live notification to targeted user
     */
    public function pushToUser(int $userId, array $notification): bool
    {
        if (empty($this->userMap[$userId])) {
            return false; // User not currently connected
        }

        $payload = json_encode([
            'event' => 'notification',
            'data'  => $notification
        ]);

        foreach ($this->userMap[$userId] as $conn) {
            $conn->send($payload);
        }

        return true;
    }
}
