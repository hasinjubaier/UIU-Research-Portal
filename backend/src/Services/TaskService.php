<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Task;
use App\Utils\Logger;
use PDO;

class TaskService
{
    private PDO $db;
    private Task $taskModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->taskModel = new Task($db);
    }

    public function getKanban(int $projectId): array
    {
        return $this->taskModel->getKanbanByProject($projectId);
    }

    public function create(array $data, int $userId): array
    {
        $data['created_by'] = $userId;
        $taskId = (int)$this->taskModel->create($data);

        Logger::info("Task created: ID {$taskId} in Project {$data['project_id']}");
        return $this->taskModel->findById($taskId);
    }

    public function update(int $id, array $data, int $userId): array
    {
        $task = $this->taskModel->findById($id);
        if (!$task) {
            throw new ResourceNotFoundException("Task not found with ID {$id}");
        }

        $this->taskModel->update($id, $data);
        Logger::info("Task updated: ID {$id} by User {$userId}");
        return $this->taskModel->findById($id);
    }

    public function updateStatus(int $id, string $status): array
    {
        $task = $this->taskModel->findById($id);
        if (!$task) {
            throw new ResourceNotFoundException("Task not found with ID {$id}");
        }

        $this->taskModel->update($id, ['status' => $status]);
        Logger::info("Task status moved: ID {$id} -> {$status}");
        return $this->taskModel->findById($id);
    }

    public function delete(int $id, int $userId): bool
    {
        $task = $this->taskModel->findById($id);
        if (!$task) {
            throw new ResourceNotFoundException("Task not found with ID {$id}");
        }

        Logger::info("Task deleted: ID {$id} by User {$userId}");
        return $this->taskModel->delete($id);
    }
}
