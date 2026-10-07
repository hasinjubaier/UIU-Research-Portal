<?php

namespace App\Services;

use App\Exceptions\AuthorizationException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Idea;
use App\Utils\Logger;
use PDO;

class IdeaService
{
    private PDO $db;
    private Idea $ideaModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->ideaModel = new Idea($db);
    }

    public function list(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        return $this->ideaModel->listWithDetails($page, $perPage, $filters);
    }

    public function get(int $id): array
    {
        $idea = $this->ideaModel->findById($id);
        if (!$idea) {
            throw new ResourceNotFoundException("Idea not found with ID {$id}");
        }

        $sStmt = $this->db->prepare("SELECT skill FROM idea_skills WHERE idea_id = ?");
        $sStmt->execute([$id]);
        $idea['skills'] = $sStmt->fetchAll(PDO::FETCH_COLUMN);

        $cStmt = $this->db->prepare("
            SELECT ic.*, u.name as user_name, u.initials as user_initials
            FROM idea_comments ic
            JOIN users u ON ic.user_id = u.id
            WHERE ic.idea_id = ? AND ic.deleted_at IS NULL
            ORDER BY ic.created_at ASC
        ");
        $cStmt->execute([$id]);
        $idea['commentsList'] = $cStmt->fetchAll(PDO::FETCH_ASSOC);

        return $idea;
    }

    public function create(array $data, int $userId): array
    {
        $data['user_id'] = $userId;
        $ideaId = (int)$this->ideaModel->create($data);

        if (!empty($data['skills']) && is_array($data['skills'])) {
            $this->ideaModel->syncSkills($ideaId, $data['skills']);
        }

        Logger::info("Idea created: ID {$ideaId} by User {$userId}");
        return $this->get($ideaId);
    }

    public function toggleUpvote(int $id, int $userId): array
    {
        $this->get($id);
        return $this->ideaModel->toggleUpvote($id, $userId);
    }

    public function addComment(int $id, int $userId, string $comment): bool
    {
        $this->get($id);

        $stmt = $this->db->prepare("
            INSERT INTO idea_comments (idea_id, user_id, comment, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$id, $userId, trim($comment)]);

        $this->db->prepare("UPDATE ideas SET comments_count = comments_count + 1 WHERE id = ?")->execute([$id]);
        Logger::info("Comment added to idea {$id} by User {$userId}");

        return true;
    }

    public function delete(int $id, int $userId): bool
    {
        $idea = $this->get($id);

        if ((int)$idea['user_id'] !== $userId) {
            throw new AuthorizationException("You can only delete your own ideas");
        }

        Logger::info("Idea deleted: ID {$id} by User {$userId}");
        return $this->ideaModel->delete($id);
    }
}
