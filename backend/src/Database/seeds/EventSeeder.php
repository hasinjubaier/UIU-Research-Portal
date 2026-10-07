<?php

namespace App\Database\Seeds;

use PDO;

class EventSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $events = [
            ['id' => 1, 'title' => 'UIU National Hackathon 2025', 'type' => 'Hackathon', 'start_date' => '2025-06-14', 'end_date' => '2025-06-15', 'location' => 'UIU Campus', 'prize' => '৳2,00,000', 'participants_count' => 320, 'spots' => 500, 'tags' => ['AI', 'Web', 'IoT'], 'status' => 'open', 'organizer' => 'UIU CSE Department'],
            ['id' => 2, 'title' => 'ICPC Asia Dhaka Regional 2025', 'type' => 'Competition', 'start_date' => '2025-07-20', 'end_date' => null, 'location' => 'BUET, Dhaka', 'prize' => 'ACM-ICPC Trophy', 'participants_count' => 180, 'spots' => 300, 'tags' => ['Competitive Programming'], 'status' => 'open', 'organizer' => 'ACM Bangladesh'],
            ['id' => 3, 'title' => 'Research Methodology Workshop', 'type' => 'Workshop', 'start_date' => '2025-05-28', 'end_date' => null, 'location' => 'UIU Auditorium', 'prize' => null, 'participants_count' => 65, 'spots' => 100, 'tags' => ['Research', 'Academic Writing', 'IEEE'], 'status' => 'open', 'organizer' => 'UIU Research Cell'],
            ['id' => 4, 'title' => 'International Conference on AI (ICAI 2025)', 'type' => 'Conference', 'start_date' => '2025-08-05', 'end_date' => '2025-08-07', 'location' => 'Virtual + Dhaka', 'prize' => null, 'participants_count' => 890, 'spots' => 1200, 'tags' => ['AI', 'ML', 'Deep Learning'], 'status' => 'open', 'organizer' => 'IEEE Bangladesh'],
            ['id' => 5, 'title' => 'Startup Pitch Competition — TechNext BD', 'type' => 'Competition', 'start_date' => '2025-06-01', 'end_date' => null, 'location' => 'ICT Division, Dhaka', 'prize' => '৳5,00,000 + Investment', 'participants_count' => 48, 'spots' => 60, 'tags' => ['Startup', 'Innovation', 'Fintech'], 'status' => 'closing-soon', 'organizer' => 'ICT Division, Bangladesh'],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO events (id, title, description, type, start_date, end_date, location, prize, spots, participants_count, status, organizer)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE title=VALUES(title)
        ");

        $tagStmt = $this->pdo->prepare("INSERT INTO event_tags (event_id, tag) VALUES (?, ?)");

        foreach ($events as $e) {
            $stmt->execute([
                $e['id'], $e['title'], "Academic and tech event organized by {$e['organizer']}",
                $e['type'], $e['start_date'], $e['end_date'], $e['location'],
                $e['prize'], $e['spots'], $e['participants_count'], $e['status'], $e['organizer']
            ]);

            foreach ($e['tags'] as $t) {
                $tagStmt->execute([$e['id'], $t]);
            }
        }
    }
}
