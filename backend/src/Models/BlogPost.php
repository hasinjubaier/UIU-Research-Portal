<?php

namespace App\Models;

use PDO;

class BlogPost extends BaseModel
{
    protected string $table = 'blog_posts';
    protected array $fillable = [
        'user_id', 'title', 'slug', 'excerpt', 'content',
        'category', 'read_time', 'likes_count', 'comments_count', 'published_at'
    ];

    public function listWithDetails(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $conditions = ["b.deleted_at IS NULL"];
        $params = [];

        if (!empty($filters['category'])) {
            $conditions[] = "b.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['query'])) {
            $conditions[] = "(b.title LIKE ? OR b.excerpt LIKE ? OR b.content LIKE ?)";
            $params[] = "%{$filters['query']}%";
            $params[] = "%{$filters['query']}%";
            $params[] = "%{$filters['query']}%";
        }

        $where = implode(' AND ', $conditions);

        $countStmt = $this->connection->prepare("SELECT COUNT(*) FROM blog_posts b WHERE {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql = "
            SELECT 
                b.*, u.name as author_name, u.initials as author_initials, u.avatar as author_avatar,
                DATE_FORMAT(b.created_at, '%Y-%m-%d') as `date`
            FROM blog_posts b
            JOIN users u ON b.user_id = u.id
            WHERE {$where}
            ORDER BY b.id DESC
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
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($posts as &$p) {
            $tStmt = $this->connection->prepare("SELECT tag FROM blog_tags WHERE post_id = ?");
            $tStmt->execute([$p['id']]);
            $p['tags'] = $tStmt->fetchAll(PDO::FETCH_COLUMN);

            $p['author'] = (int)$p['user_id'];
            $p['readTime'] = $p['read_time'];
            $p['likes'] = (int)$p['likes_count'];
            $p['comments'] = (int)$p['comments_count'];
        }

        return [
            'data'       => $posts,
            'pagination' => [
                'page'     => $page,
                'per_page' => $perPage,
                'total'    => $total,
                'pages'    => ceil($total / max(1, $perPage)),
                'has_more' => $page < ceil($total / max(1, $perPage))
            ]
        ];
    }

    public function findWithComments(int $id): ?array
    {
        $post = $this->findById($id);
        if (!$post) {
            return null;
        }

        // Author
        $userStmt = $this->connection->prepare("SELECT name, initials, avatar, department FROM users WHERE id = ?");
        $userStmt->execute([$post['user_id']]);
        $post['authorDetails'] = $userStmt->fetch(PDO::FETCH_ASSOC);

        // Tags
        $tStmt = $this->connection->prepare("SELECT tag FROM blog_tags WHERE post_id = ?");
        $tStmt->execute([$id]);
        $post['tags'] = $tStmt->fetchAll(PDO::FETCH_COLUMN);

        // Comments
        $cStmt = $this->connection->prepare("
            SELECT c.*, u.name as user_name, u.initials as user_initials, u.avatar as user_avatar
            FROM blog_comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = ? AND c.deleted_at IS NULL
            ORDER BY c.created_at ASC
        ");
        $cStmt->execute([$id]);
        $post['commentsList'] = $cStmt->fetchAll(PDO::FETCH_ASSOC);

        $post['author'] = (int)$post['user_id'];
        $post['readTime'] = $post['read_time'];
        $post['likes'] = (int)$post['likes_count'];
        $post['comments'] = count($post['commentsList']);

        return $post;
    }

    public function toggleLike(int $postId, int $userId): array
    {
        $stmt = $this->connection->prepare("SELECT id FROM blog_likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        $likeId = $stmt->fetchColumn();

        if ($likeId) {
            $this->connection->prepare("DELETE FROM blog_likes WHERE id = ?")->execute([$likeId]);
            $this->connection->prepare("UPDATE blog_posts SET likes_count = GREATEST(0, likes_count - 1) WHERE id = ?")->execute([$postId]);
            $liked = false;
        } else {
            $this->connection->prepare("INSERT INTO blog_likes (post_id, user_id) VALUES (?, ?)")->execute([$postId, $userId]);
            $this->connection->prepare("UPDATE blog_posts SET likes_count = likes_count + 1 WHERE id = ?")->execute([$postId]);
            $liked = true;
        }

        $cntStmt = $this->connection->prepare("SELECT likes_count FROM blog_posts WHERE id = ?");
        $cntStmt->execute([$postId]);
        $count = (int)$cntStmt->fetchColumn();

        return ['liked' => $liked, 'likes' => $count];
    }

    public function syncTags(int $postId, array $tags): void
    {
        $this->connection->prepare("DELETE FROM blog_tags WHERE post_id = ?")->execute([$postId]);
        $stmt = $this->connection->prepare("INSERT INTO blog_tags (post_id, tag) VALUES (?, ?)");
        foreach ($tags as $tag) {
            $stmt->execute([$postId, trim($tag)]);
        }
    }
}
