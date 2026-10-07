<?php

namespace App\Services;

use App\Exceptions\AuthorizationException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Utils\Logger;
use PDO;

class ProjectService
{
    private PDO $db;
    private Project $projectModel;
    private ProjectMember $memberModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->projectModel = new Project($db);
        $this->memberModel = new ProjectMember($db);
    }

    public function list(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        return $this->projectModel->listWithDetails($page, $perPage, $filters);
    }

    public function get(int $id): array
    {
        $project = $this->projectModel->findWithDetails($id);
        if (!$project) {
            throw new ResourceNotFoundException("Project not found with ID {$id}");
        }
        return $project;
    }

    public function create(array $data, int $userId): array
    {
        $data['created_by'] = $userId;
        $projectId = (int)$this->projectModel->create($data);

        // Add creator as lead
        $this->memberModel->addMember($projectId, $userId, 'lead');

        // Add tags
        if (!empty($data['tags']) && is_array($data['tags'])) {
            $this->projectModel->syncTags($projectId, $data['tags']);
        }

        // Add additional members
        if (!empty($data['members']) && is_array($data['members'])) {
            foreach ($data['members'] as $memberId) {
                if ((int)$memberId !== $userId) {
                    $this->memberModel->addMember($projectId, (int)$memberId, 'contributor');
                }
            }
        }

        Logger::info("Project created: ID {$projectId} by User {$userId}");
        return $this->get($projectId);
    }

    public function update(int $id, array $data, int $userId): array
    {
        if (!$this->canEdit($id, $userId)) {
            throw new AuthorizationException("You do not have permission to edit this project");
        }

        $this->projectModel->update($id, $data);

        if (isset($data['tags']) && is_array($data['tags'])) {
            $this->projectModel->syncTags($id, $data['tags']);
        }

        Logger::info("Project updated: ID {$id} by User {$userId}");
        return $this->get($id);
    }

    public function delete(int $id, int $userId): bool
    {
        $project = $this->projectModel->findById($id);
        if (!$project) {
            throw new ResourceNotFoundException("Project not found with ID {$id}");
        }

        if ((int)$project['created_by'] !== $userId) {
            throw new AuthorizationException("Only the project lead/owner can delete this project");
        }

        Logger::info("Project deleted: ID {$id} by User {$userId}");
        return $this->projectModel->delete($id);
    }

    public function canEdit(int $projectId, int $userId): bool
    {
        $project = $this->projectModel->findById($projectId);
        if (!$project) {
            return false;
        }

        if ((int)$project['created_by'] === $userId) {
            return true;
        }

        $role = $this->memberModel->getRole($projectId, $userId);
        return in_array($role, ['lead', 'co-author']);
    }

    public function addMember(int $projectId, int $userId, string $role = 'contributor'): bool
    {
        $this->get($projectId);
        return $this->memberModel->addMember($projectId, $userId, $role);
    }

    public function removeMember(int $projectId, int $userId): bool
    {
        $this->get($projectId);
        return $this->memberModel->removeMember($projectId, $userId);
    }
}
