<?php

namespace App\Services;

use App\Exceptions\AuthorizationException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Utils\Logger;
use PDO;

class BlogService
{
    private PDO $db;
    private BlogPost $blogModel;
    private BlogComment $commentModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->blogModel = new BlogPost($db);
        $this->commentModel = new BlogComment($db);
    }

    public function list(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        return $this->blogModel->listWithDetails($page, $perPage, $filters);
    }

    public function get(int $id): array
    {
        $post = $this->blogModel->findWithComments($id);
        if (!$post) {
            throw new ResourceNotFoundException("Blog post not found with ID {$id}");
        }
        return $post;
    }

    public function create(array $data, int $userId): array
    {
        $data['user_id'] = $userId;
        if (empty($data['excerpt']) && !empty($data['content'])) {
            $data['excerpt'] = substr(strip_tags($data['content']), 0, 160) . '...';
        }

        $postId = (int)$this->blogModel->create($data);

        if (!empty($data['tags']) && is_array($data['tags'])) {
            $this->blogModel->syncTags($postId, $data['tags']);
        }

        Logger::info("Blog post created: ID {$postId} by User {$userId}");
        return $this->get($postId);
    }

    public function toggleLike(int $postId, int $userId): array
    {
        $this->get($postId);
        return $this->blogModel->toggleLike($postId, $userId);
    }

    public function addComment(int $postId, int $userId, string $comment, ?int $parentId = null): array
    {
        $this->get($postId);

        $commentId = $this->commentModel->create([
            'post_id'   => $postId,
            'user_id'   => $userId,
            'comment'   => trim($comment),
            'parent_id' => $parentId,
        ]);

        $this->db->prepare("UPDATE blog_posts SET comments_count = comments_count + 1 WHERE id = ?")->execute([$postId]);
        Logger::info("Comment added to blog post {$postId} by User {$userId}");

        return $this->commentModel->findById($commentId);
    }

    public function delete(int $id, int $userId): bool
    {
        $post = $this->get($id);

        if ((int)$post['user_id'] !== $userId) {
            throw new AuthorizationException("You can only delete your own blog posts");
        }

        Logger::info("Blog post deleted: ID {$id} by User {$userId}");
        return $this->blogModel->delete($id);
    }
}
