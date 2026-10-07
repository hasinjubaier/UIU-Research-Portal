<?php

namespace App\Database\Seeds;

use PDO;

class DatabaseSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        (new UserSeeder($this->pdo))->run();
        (new ProjectSeeder($this->pdo))->run();
        (new TaskSeeder($this->pdo))->run();
        (new ResourceSeeder($this->pdo))->run();
        (new BlogSeeder($this->pdo))->run();
        (new IdeaSeeder($this->pdo))->run();
        (new EventSeeder($this->pdo))->run();

        $this->seedBadges();
        $this->seedMessages();
        $this->seedNotifications();
    }

    private function seedBadges(): void
    {
        $badges = [
            ['id' => 1, 'name' => 'Active Researcher', 'icon' => 'AR', 'color' => 'accent', 'description' => 'Contributed to 5+ projects'],
            ['id' => 2, 'name' => 'Top Contributor',   'icon' => 'TC', 'color' => 'orange', 'description' => 'Ranked top 10 in contributions'],
            ['id' => 3, 'name' => 'Knowledge Sharer',  'icon' => 'KS', 'color' => 'purple', 'description' => 'Shared 3+ quality resources'],
            ['id' => 4, 'name' => 'Idea Innovator',    'icon' => 'II', 'color' => 'teal',   'description' => 'Posted an idea with 50+ upvotes'],
            ['id' => 5, 'name' => 'Team Player',       'icon' => 'TP', 'color' => 'green',  'description' => 'Completed 3 team projects'],
            ['id' => 6, 'name' => 'Research Pioneer',  'icon' => 'RP', 'color' => 'accent', 'description' => 'Published a research paper'],
            ['id' => 7, 'name' => 'Data Champion',     'icon' => 'DC', 'color' => 'purple', 'description' => 'Shared 5+ datasets'],
            ['id' => 8, 'name' => 'Community Leader',  'icon' => 'CL', 'color' => 'orange', 'description' => 'Reach top 3 on leaderboard'],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO badges (id, name, icon, color, description)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE name=VALUES(name)
        ");

        foreach ($badges as $b) {
            $stmt->execute([$b['id'], $b['name'], $b['icon'], $b['color'], $b['description']]);
        }

        $userBadgeStmt = $this->pdo->prepare("
            INSERT IGNORE INTO user_badges (user_id, badge_id) VALUES (?, ?)
        ");
        foreach ([1, 2, 3, 4] as $bid) {
            $userBadgeStmt->execute([1, $bid]);
        }
    }

    private function seedMessages(): void
    {
        $convs = [
            ['id' => 1, 'type' => 'group', 'name' => 'AI Healthcare Team', 'members' => [1, 5, 7], 'lastMessage' => 'Rafsan: Let\'s sync at 7PM today'],
            ['id' => 2, 'type' => 'dm', 'name' => null, 'members' => [1, 5], 'lastMessage' => 'Can you push the model weights?'],
            ['id' => 3, 'type' => 'dm', 'name' => null, 'members' => [1, 2], 'lastMessage' => 'I saw your idea on the marketplace!'],
            ['id' => 4, 'type' => 'group', 'name' => 'Smart Campus Project', 'members' => [3, 2, 8, 1], 'lastMessage' => 'Tanvir: PR is ready for review'],
            ['id' => 5, 'type' => 'dm', 'name' => null, 'members' => [1, 8], 'lastMessage' => 'Blockchain deployment went live '],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO conversations (id, type, name, last_message, last_message_at)
            VALUES (?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE last_message=VALUES(last_message)
        ");

        $partStmt = $this->pdo->prepare("
            INSERT INTO conversation_participants (conversation_id, user_id)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE user_id=VALUES(user_id)
        ");

        $msgStmt = $this->pdo->prepare("
            INSERT INTO messages (conversation_id, sender_id, message, is_read)
            VALUES (?, ?, ?, 1)
        ");

        foreach ($convs as $c) {
            $stmt->execute([$c['id'], $c['type'], $c['name'], $c['lastMessage']]);

            foreach ($c['members'] as $m) {
                $partStmt->execute([$c['id'], $m]);
            }

            $msgStmt->execute([$c['id'], $c['members'][0], $c['lastMessage']]);
        }
    }

    private function seedNotifications(): void
    {
        $notifs = [
            ['id' => 1, 'user_id' => 1, 'type' => 'collab',   'icon' => 'collab',   'text' => 'Nusrat Jahan sent you a collaboration request', 'is_read' => 0],
            ['id' => 2, 'user_id' => 1, 'type' => 'project',  'icon' => 'project',  'text' => 'New task assigned: Train CNN baseline model', 'is_read' => 0],
            ['id' => 3, 'user_id' => 1, 'type' => 'message',  'icon' => 'message',  'text' => 'Rakibul mentioned you in AI Healthcare Team', 'is_read' => 0],
            ['id' => 4, 'user_id' => 1, 'type' => 'idea',     'icon' => 'idea',     'text' => 'Your idea received 10 new upvotes', 'is_read' => 1],
            ['id' => 5, 'user_id' => 1, 'type' => 'blog',     'icon' => 'blog',     'text' => 'Sadia liked your blog post', 'is_read' => 1],
            ['id' => 6, 'user_id' => 1, 'type' => 'resource', 'icon' => 'resource', 'text' => 'Your dataset was downloaded 50 times today', 'is_read' => 1],
            ['id' => 7, 'user_id' => 1, 'type' => 'event',    'icon' => 'event',    'text' => 'UIU National Hackathon registration closes in 3 days', 'is_read' => 1],
            ['id' => 8, 'user_id' => 1, 'type' => 'system',   'icon' => 'system',   'text' => 'You earned the Active Researcher badge', 'is_read' => 1],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO notifications (id, user_id, type, icon, text, is_read)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE text=VALUES(text), is_read=VALUES(is_read)
        ");

        foreach ($notifs as $n) {
            $stmt->execute([$n['id'], $n['user_id'], $n['type'], $n['icon'], $n['text'], $n['is_read']]);
        }
    }
}
