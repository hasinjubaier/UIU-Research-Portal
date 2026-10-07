<?php

namespace App\Models;

use PDO;

class User extends BaseModel
{
    protected string $table = 'users';
    protected array $fillable = [
        'name', 'email', 'password_hash', 'avatar', 'initials',
        'department', 'academic_year', 'role', 'bio', 'reputation',
        'status', 'email_verified_at'
    ];

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM users
            WHERE email = ? AND deleted_at IS NULL
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function getFullProfile(int $userId): ?array
    {
        $user = $this->findById($userId);
        if (!$user) {
            return null;
        }

        unset($user['password_hash']);

        // Skills
        $stmt = $this->connection->prepare("SELECT skill FROM user_skills WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user['skills'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Interests
        $stmt = $this->connection->prepare("SELECT interest FROM user_interests WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user['interests'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Looking for
        $stmt = $this->connection->prepare("SELECT looking_for FROM user_looking_for WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user['lookingFor'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Badges
        $stmt = $this->connection->prepare("
            SELECT b.*, ub.earned_at
            FROM badges b
            JOIN user_badges ub ON b.id = ub.badge_id
            WHERE ub.user_id = ?
        ");
        $stmt->execute([$userId]);
        $user['badges'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate rank based on reputation
        $stmt = $this->connection->prepare("
            SELECT COUNT(*) + 1 as `rank`
            FROM users
            WHERE reputation > ? AND deleted_at IS NULL
        ");
        $stmt->execute([$user['reputation'] ?? 0]);
        $user['rank'] = (int)$stmt->fetchColumn();

        // Project counts
        $stmt = $this->connection->prepare("
            SELECT 
                COUNT(CASE WHEN p.status = 'completed' THEN 1 END) as completed_projects,
                COUNT(CASE WHEN p.status != 'completed' THEN 1 END) as ongoing_projects
            FROM project_members pm
            JOIN projects p ON pm.project_id = p.id
            WHERE pm.user_id = ? AND p.deleted_at IS NULL
        ");
        $stmt->execute([$userId]);
        $counts = $stmt->fetch(PDO::FETCH_ASSOC);
        $user['completedProjects'] = (int)($counts['completed_projects'] ?? 0);
        $user['ongoingProjects'] = (int)($counts['ongoing_projects'] ?? 0);

        return $user;
    }

    public function getLeaderboard(int $limit = 10): array
    {
        $stmt = $this->connection->prepare("
            SELECT id, name, initials, department, academic_year, reputation, status
            FROM users
            WHERE deleted_at IS NULL
            ORDER BY reputation DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $leaderboard = [];
        foreach ($users as $index => $u) {
            $leaderboard[] = [
                'rank'    => $index + 1,
                'student' => (int)$u['id'],
                'user'    => $u,
                'points'  => (int)$u['reputation'],
                'change'  => 'same',
            ];
        }

        return $leaderboard;
    }

    public function syncSkills(int $userId, array $skills): void
    {
        $this->connection->prepare("DELETE FROM user_skills WHERE user_id = ?")->execute([$userId]);
        $stmt = $this->connection->prepare("INSERT INTO user_skills (user_id, skill) VALUES (?, ?)");
        foreach ($skills as $skill) {
            $stmt->execute([$userId, trim($skill)]);
        }
    }

    public function syncInterests(int $userId, array $interests): void
    {
        $this->connection->prepare("DELETE FROM user_interests WHERE user_id = ?")->execute([$userId]);
        $stmt = $this->connection->prepare("INSERT INTO user_interests (user_id, interest) VALUES (?, ?)");
        foreach ($interests as $interest) {
            $stmt->execute([$userId, trim($interest)]);
        }
    }

    public function search(string $query, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $conditions = ["u.deleted_at IS NULL"];
        $params = [];

        if ($query !== '') {
            $conditions[] = "(u.name LIKE ? OR u.department LIKE ? OR u.bio LIKE ?)";
            $params[] = "%{$query}%";
            $params[] = "%{$query}%";
            $params[] = "%{$query}%";
        }

        if (!empty($filters['department'])) {
            $conditions[] = "u.department = ?";
            $params[] = $filters['department'];
        }

        if (!empty($filters['year'])) {
            $conditions[] = "u.academic_year = ?";
            $params[] = $filters['year'];
        }

        $where = implode(' AND ', $conditions);

        // Count
        $countStmt = $this->connection->prepare("SELECT COUNT(DISTINCT u.id) FROM users u WHERE {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Fetch
        $sql = "
            SELECT u.id, u.name, u.email, u.avatar, u.initials, u.department, u.academic_year, u.role, u.bio, u.reputation, u.status
            FROM users u
            WHERE {$where}
            ORDER BY u.reputation DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->connection->prepare($sql);
        $idx = 1;
        foreach ($params as $p) {
            $stmt->bindValue($idx++, $p);
        }
        $stmt->bindValue($idx++, $perPage, PDO::PARAM_INT);
        $stmt->bindValue($idx, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Attach skills
        foreach ($users as &$u) {
            $sStmt = $this->connection->prepare("SELECT skill FROM user_skills WHERE user_id = ?");
            $sStmt->execute([$u['id']]);
            $u['skills'] = $sStmt->fetchAll(PDO::FETCH_COLUMN);

            $lfStmt = $this->connection->prepare("SELECT looking_for FROM user_looking_for WHERE user_id = ?");
            $lfStmt->execute([$u['id']]);
            $u['lookingFor'] = $lfStmt->fetchAll(PDO::FETCH_COLUMN);
        }

        return [
            'data'       => $users,
            'pagination' => [
                'page'     => $page,
                'per_page' => $perPage,
                'total'    => $total,
                'pages'    => ceil($total / max(1, $perPage)),
                'has_more' => $page < ceil($total / max(1, $perPage))
            ]
        ];
    }
}
