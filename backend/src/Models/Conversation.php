<?php

namespace App\Models;

use PDO;

class Conversation extends BaseModel
{
    protected string $table = 'conversations';
    protected array $fillable = ['type', 'name', 'last_message', 'last_message_at'];

    public function listForUser(int $userId): array
    {
        $stmt = $this->connection->prepare("
            SELECT 
                c.id, c.type, c.name, c.last_message, c.last_message_at,
                cp.unread_count
            FROM conversations c
            JOIN conversation_participants cp ON c.id = cp.conversation_id
            WHERE cp.user_id = ? AND c.deleted_at IS NULL
            ORDER BY c.last_message_at DESC, c.id DESC
        ");
        $stmt->execute([$userId]);
        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($conversations as &$conv) {
            // Get all participants
            $partStmt = $this->connection->prepare("
                SELECT u.id, u.name, u.initials, u.avatar, u.status
                FROM conversation_participants cp
                JOIN users u ON cp.user_id = u.id
                WHERE cp.conversation_id = ?
            ");
            $partStmt->execute([$conv['id']]);
            $participants = $partStmt->fetchAll(PDO::FETCH_ASSOC);

            $conv['members'] = array_map(fn($p) => (int)$p['id'], $participants);
            $conv['participants'] = $participants;

            if ($conv['type'] === 'dm') {
                $other = array_filter($participants, fn($p) => (int)$p['id'] !== $userId);
                $otherUser = reset($other) ?: null;
                $conv['with'] = $otherUser ? (int)$otherUser['id'] : null;
                $conv['name'] = $otherUser ? $otherUser['name'] : 'Direct Message';
            }
        }

        return $conversations;
    }

    public function findOrCreateDm(int $userId1, int $userId2): array
    {
        // Check if existing DM exists between user1 and user2
        $stmt = $this->connection->prepare("
            SELECT c.id FROM conversations c
            JOIN conversation_participants cp1 ON c.id = cp1.conversation_id AND cp1.user_id = ?
            JOIN conversation_participants cp2 ON c.id = cp2.conversation_id AND cp2.user_id = ?
            WHERE c.type = 'dm' AND c.deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([$userId1, $userId2]);
        $convId = $stmt->fetchColumn();

        if ($convId) {
            return $this->findById($convId);
        }

        // Create new
        $this->connection->beginTransaction();
        try {
            $convId = $this->create(['type' => 'dm', 'name' => null]);
            $partStmt = $this->connection->prepare("INSERT INTO conversation_participants (conversation_id, user_id) VALUES (?, ?)");
            $partStmt->execute([$convId, $userId1]);
            $partStmt->execute([$convId, $userId2]);
            $this->connection->commit();

            return $this->findById($convId);
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
