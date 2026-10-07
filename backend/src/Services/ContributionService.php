<?php

namespace App\Services;

use App\Models\Contribution;
use App\Utils\Logger;
use PDO;

class ContributionService
{
    private PDO $db;
    private Contribution $contribModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->contribModel = new Contribution($db);
    }

    public function getByProject(int $projectId): array
    {
        return $this->contribModel->getByProject($projectId);
    }

    public function logActivity(int $projectId, int $userId, string $actionType, int $points = 10): void
    {
        $column = match($actionType) {
            'edit'    => 'edits',
            'upload'  => 'uploads',
            'task'    => 'tasks',
            'comment' => 'comments',
            default   => 'edits'
        };

        $stmt = $this->db->prepare("
            INSERT INTO contributions (project_id, user_id, {$column}, score)
            VALUES (?, ?, 1, ?)
            ON DUPLICATE KEY UPDATE 
                {$column} = {$column} + 1,
                score = score + ?
        ");
        $stmt->execute([$projectId, $userId, $points, $points]);

        Logger::info("Contribution logged for Project {$projectId}, User {$userId}, Type: {$actionType}");
    }
}
