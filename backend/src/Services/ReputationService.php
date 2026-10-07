<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserBadge;
use App\Utils\Logger;
use PDO;

class ReputationService
{
    private PDO $db;
    private User $userModel;
    private UserBadge $badgeModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->badgeModel = new UserBadge($db);
    }

    public function addPoints(int $userId, string $action, int $points): void
    {
        $this->db->beginTransaction();
        try {
            // Log reputation
            $stmt = $this->db->prepare("
                INSERT INTO reputation_logs (user_id, action, points)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$userId, $action, $points]);

            // Update user total
            $uStmt = $this->db->prepare("
                UPDATE users
                SET reputation = reputation + ?
                WHERE id = ?
            ");
            $uStmt->execute([$points, $userId]);

            $this->db->commit();
            Logger::info("Awarded {$points} reputation points to User {$userId} for: {$action}");
        } catch (\Throwable $e) {
            $this->db->rollBack();
            Logger::error("Failed awarding reputation points: " . $e->getMessage());
        }
    }

    public function getReputationBreakdown(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT action, points, DATE_FORMAT(created_at, '%Y-%m-%d') as `date`
            FROM reputation_logs
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 20
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBadges(int $userId): array
    {
        return $this->badgeModel->getAllBadgesWithUserStatus($userId);
    }

    public function awardBadge(int $userId, int $badgeId): bool
    {
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO user_badges (user_id, badge_id, earned_at)
            VALUES (?, ?, NOW())
        ");
        $success = $stmt->execute([$userId, $badgeId]);
        if ($success) {
            Logger::info("Awarded badge ID {$badgeId} to User {$userId}");
        }
        return $success;
    }
}
