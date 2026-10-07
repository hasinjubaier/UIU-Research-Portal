<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Conversation;
use App\Models\Message;
use App\Utils\Logger;
use PDO;

class MessageService
{
    private PDO $db;
    private Conversation $convModel;
    private Message $msgModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->convModel = new Conversation($db);
        $this->msgModel = new Message($db);
    }

    public function getUserConversations(int $userId): array
    {
        return $this->convModel->listForUser($userId);
    }

    public function getConversationMessages(int $conversationId, int $userId, int $page = 1, int $perPage = 50): array
    {
        $conv = $this->convModel->findById($conversationId);
        if (!$conv) {
            throw new ResourceNotFoundException("Conversation not found with ID {$conversationId}");
        }

        $messages = $this->msgModel->getByConversation($conversationId, $page, $perPage);
        $this->msgModel->markAsRead($conversationId, $userId);

        return $messages;
    }

    public function sendMessage(int $conversationId, int $senderId, string $message): array
    {
        $conv = $this->convModel->findById($conversationId);
        if (!$conv) {
            throw new ResourceNotFoundException("Conversation not found with ID {$conversationId}");
        }

        $text = trim($message);

        // Insert message
        $msgId = $this->msgModel->create([
            'conversation_id' => $conversationId,
            'sender_id'       => $senderId,
            'message'         => $text,
            'is_read'         => 0,
        ]);

        // Update conversation summary
        $this->convModel->update($conversationId, [
            'last_message'    => $text,
            'last_message_at' => date('Y-m-d H:i:s'),
        ]);

        // Increment unread count for other participants
        $stmt = $this->db->prepare("
            UPDATE conversation_participants
            SET unread_count = unread_count + 1
            WHERE conversation_id = ? AND user_id != ?
        ");
        $stmt->execute([$conversationId, $senderId]);

        Logger::info("Message sent in conversation {$conversationId} by User {$senderId}");
        return $this->msgModel->findById($msgId);
    }

    public function startDm(int $user1, int $user2): array
    {
        return $this->convModel->findOrCreateDm($user1, $user2);
    }

    public function markAsRead(int $conversationId, int $userId): void
    {
        $this->msgModel->markAsRead($conversationId, $userId);
    }
}
