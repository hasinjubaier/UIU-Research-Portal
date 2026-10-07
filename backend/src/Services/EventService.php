<?php

namespace App\Services;

use App\Exceptions\AuthorizationException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Event;
use App\Utils\Logger;
use PDO;

class EventService
{
    private PDO $db;
    private Event $eventModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->eventModel = new Event($db);
    }

    public function list(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        return $this->eventModel->listWithDetails($page, $perPage, $filters);
    }

    public function get(int $id): array
    {
        $event = $this->eventModel->findById($id);
        if (!$event) {
            throw new ResourceNotFoundException("Event not found with ID {$id}");
        }

        $tStmt = $this->db->prepare("SELECT tag FROM event_tags WHERE event_id = ?");
        $tStmt->execute([$id]);
        $event['tags'] = $tStmt->fetchAll(PDO::FETCH_COLUMN);

        return $event;
    }

    public function create(array $data, int $userId): array
    {
        $data['created_by'] = $userId;
        $eventId = (int)$this->eventModel->create($data);

        if (!empty($data['tags']) && is_array($data['tags'])) {
            $this->eventModel->syncTags($eventId, $data['tags']);
        }

        Logger::info("Event created: ID {$eventId} by User {$userId}");
        return $this->get($eventId);
    }

    public function register(int $id, int $userId): bool
    {
        $this->get($id);
        return $this->eventModel->registerUser($id, $userId);
    }

    public function delete(int $id, int $userId): bool
    {
        $event = $this->get($id);

        if ((int)$event['created_by'] !== $userId) {
            throw new AuthorizationException("Only the event creator can delete this event");
        }

        Logger::info("Event deleted: ID {$id} by User {$userId}");
        return $this->eventModel->delete($id);
    }
}
