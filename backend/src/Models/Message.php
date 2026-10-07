<?php

namespace App\Models;

use PDO;

class Message extends BaseModel
{
    protected string $table = 'messages';
    protected array $fillable = ['conversation_id', 'sender_id', 'message', 'is_read'];

    public function getByConversation(int $conversationId, int $page = 1, int $perPage = 50): array
    {
        $offset = max(0, ($page - 1) * $perPage);

        $stmt = $this->connection->prepare("
            SELECT m.*, u.name as sender_name, u.initials as sender_initials, u.avatar as sender_avatar
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.conversation_id = ? AND m.deleted_at IS NULL
            ORDER BY m.created_at ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $conversationId, PDO::PARAM_INT);
        $stmt->bindValue(2, $perPage, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead(int $conversationId, int $userId): void
    {
        // Mark messages not sent by userId as read
        $stmt = $this->connection->prepare("
            UPDATE messages
            SET is_read = 1
            WHERE conversation_id = ? AND sender_id != ? AND is_read = 0
        ");
        $stmt->execute([$conversationId, $userId]);

        // Reset unread count for user in conversation_participants
        $pStmt = $this->connection->prepare("
            UPDATE conversation_participants
            SET unread_count = 0
            WHERE conversation_id = ? AND user_id = ?
        ");
        $pStmt->execute([$conversationId, $userId]);
    }
}
