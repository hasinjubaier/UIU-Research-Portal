<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Models\User;
use App\Utils\Logger;
use PDO;

class UserService
{
    private PDO $db;
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    public function getProfile(int $userId): array
    {
        $profile = $this->userModel->getFullProfile($userId);
        if (!$profile) {
            throw new ResourceNotFoundException("User not found with ID {$userId}");
        }
        return $profile;
    }

    public function updateProfile(int $userId, array $data): array
    {
        $user = $this->userModel->findById($userId);
        if (!$user) {
            throw new ResourceNotFoundException("User not found with ID {$userId}");
        }

        $this->userModel->update($userId, $data);

        if (isset($data['skills']) && is_array($data['skills'])) {
            $this->userModel->syncSkills($userId, $data['skills']);
        }

        if (isset($data['interests']) && is_array($data['interests'])) {
            $this->userModel->syncInterests($userId, $data['interests']);
        }

        Logger::info("User profile updated: ID {$userId}");
        return $this->userModel->getFullProfile($userId);
    }

    public function search(string $query, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        return $this->userModel->search($query, $filters, $page, $perPage);
    }

    public function getLeaderboard(int $limit = 10): array
    {
        return $this->userModel->getLeaderboard($limit);
    }
}
