<?php

namespace App\Services;

use App\Models\Contribution;
use PDO;

class AnalyticsService
{
    private PDO $db;
    private Contribution $contribModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->contribModel = new Contribution($db);
    }

    public function getDashboardStats(int $userId): array
    {
        // Total projects
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM project_members pm
            JOIN projects p ON pm.project_id = p.id
            WHERE pm.user_id = ? AND p.deleted_at IS NULL
        ");
        $stmt->execute([$userId]);
        $activeProjects = (int)$stmt->fetchColumn();

        // Tasks pending/todo
        $tStmt = $this->db->prepare("
            SELECT COUNT(*) FROM tasks
            WHERE assignee_id = ? AND status != 'done' AND deleted_at IS NULL
        ");
        $tStmt->execute([$userId]);
        $pendingTasks = (int)$tStmt->fetchColumn();

        // Total resources shared
        $rStmt = $this->db->prepare("
            SELECT COUNT(*), COALESCE(SUM(downloads_count), 0) as total_downloads
            FROM resources
            WHERE user_id = ? AND deleted_at IS NULL
        ");
        $rStmt->execute([$userId]);
        $resRow = $rStmt->fetch(PDO::FETCH_ASSOC);

        // Unread notifications
        $nStmt = $this->db->prepare("
            SELECT COUNT(*) FROM notifications
            WHERE user_id = ? AND is_read = 0 AND deleted_at IS NULL
        ");
        $nStmt->execute([$userId]);
        $unreadNotifs = (int)$nStmt->fetchColumn();

        return [
            'activeProjects'  => $activeProjects,
            'pendingTasks'    => $pendingTasks,
            'sharedResources' => (int)($resRow['COUNT(*)'] ?? 0),
            'totalDownloads'  => (int)($resRow['total_downloads'] ?? 0),
            'unreadNotifications' => $unreadNotifs,
        ];
    }

    public function getProjectContributions(int $projectId): array
    {
        return $this->contribModel->getByProject($projectId);
    }
}
