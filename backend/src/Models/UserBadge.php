<?php

namespace App\Models;

use PDO;

class UserBadge extends BaseModel
{
    protected string $table = 'user_badges';
    protected array $fillable = ['user_id', 'badge_id', 'earned_at'];
    protected bool $hasSoftDelete = false;

    public function getAllBadgesWithUserStatus(int $userId): array
    {
        $stmt = $this->connection->prepare("
            SELECT 
                b.id, b.name, b.icon, b.color, b.description as `desc`,
                CASE WHEN ub.id IS NOT NULL THEN 1 ELSE 0 END as earned
            FROM badges b
            LEFT JOIN user_badges ub ON b.id = ub.badge_id AND ub.user_id = ?
            ORDER BY b.id ASC
        ");
        $stmt->execute([$userId]);
        $badges = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($badges as &$b) {
            $b['id'] = (int)$b['id'];
            $b['earned'] = (bool)$b['earned'];
        }

        return $badges;
    }
}
