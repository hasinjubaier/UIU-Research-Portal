<?php

namespace App\WebSocket;

use App\Utils\JWT;
use App\Utils\Logger;
use SplObjectStorage;

class ChatHandler
{
    private SplObjectStorage $clients;
    private array $userSockets = []; // [userId => [connId => conn]]
    private array $roomSubscriptions = []; // [convId => [connId => conn]]

    public function __construct()
    {
        $this->clients = new SplObjectStorage();
    }

    public function onOpen($conn): void
    {
        $this->clients->attach($conn);
        Logger::info("New WebSocket connection: ({$conn->resourceId})");
    }

    public function onMessage($from, string $msg): void
    {
        $payload = json_decode($msg, true);
        if (!is_array($payload) || empty($payload['action'])) {
            return;
        }

        switch ($payload['action']) {
            case 'authenticate':
                $token = $payload['token'] ?? '';
                $decoded = JWT::decode($token);
                if ($decoded && !empty($decoded['sub'])) {
                    $userId = (int)$decoded['sub'];
                    $from->userId = $userId;
                    $this->userSockets[$userId][$from->resourceId] = $from;

                    $from->send(json_encode([
                        'event'     => 'authenticated',
                        'userId'    => $userId,
                        'timestamp' => time()
                    ]));
                    Logger::info("WebSocket client ({$from->resourceId}) authenticated as User {$userId}");
                } else {
                    $from->send(json_encode(['error' => 'Authentication failed']));
                }
                break;

            case 'join_conversation':
                $convId = (int)($payload['conversation_id'] ?? 0);
                if ($convId > 0) {
                    $this->roomSubscriptions[$convId][$from->resourceId] = $from;
                    $from->send(json_encode([
                        'event'           => 'joined_conversation',
                        'conversation_id' => $convId
                    ]));
                }
                break;

            case 'broadcast_message':
                $convId = (int)($payload['conversation_id'] ?? 0);
                $messageData = $payload['data'] ?? [];

                if ($convId > 0 && isset($this->roomSubscriptions[$convId])) {
                    $broadcastPayload = json_encode([
                        'event'           => 'new_message',
                        'conversation_id' => $convId,
                        'data'            => $messageData
                    ]);

                    foreach ($this->roomSubscriptions[$convId] as $client) {
                        $client->send($broadcastPayload);
                    }
                }
                break;
        }
    }

    public function onClose($conn): void
    {
        $this->clients->detach($conn);

        if (isset($conn->userId) && isset($this->userSockets[$conn->userId][$conn->resourceId])) {
            unset($this->userSockets[$conn->userId][$conn->resourceId]);
        }

        foreach ($this->roomSubscriptions as $convId => &$subscribers) {
            unset($subscribers[$conn->resourceId]);
        }

        Logger::info("WebSocket connection closed: ({$conn->resourceId})");
    }

    public function onError($conn, \Throwable $e): void
    {
        Logger::error("WebSocket error on connection {$conn->resourceId}: " . $e->getMessage());
        $conn->close();
    }
}
