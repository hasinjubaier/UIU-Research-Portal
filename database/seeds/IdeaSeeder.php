<?php

namespace App\Database\Seeds;

use PDO;

class IdeaSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $ideas = [
            ['id' => 1, 'user_id' => 5, 'title' => 'AI-Based Traffic Signal Optimization for Dhaka', 'upvotes_count' => 134, 'comments_count' => 28, 'domain' => 'AI / Smart City', 'skills' => ['Computer Vision', 'Reinforcement Learning', 'Python'], 'status' => 'Open', 'description' => 'Using live camera feeds and RL to dynamically optimize traffic signal timing and reduce congestion in Dhaka city.'],
            ['id' => 2, 'user_id' => 7, 'title' => 'Bangla Sign Language Recognition App', 'upvotes_count' => 98, 'comments_count' => 19, 'domain' => 'NLP / Accessibility', 'skills' => ['Computer Vision', 'MediaPipe', 'Mobile Dev'], 'status' => 'Open', 'description' => 'A smartphone app that translates Bangla sign language gestures into text/speech in real time.'],
            ['id' => 3, 'user_id' => 4, 'title' => 'University Food Waste Reduction System', 'upvotes_count' => 61, 'comments_count' => 11, 'domain' => 'IoT / Sustainability', 'skills' => ['IoT', 'Data Analysis', 'App Dev'], 'status' => 'In Progress', 'description' => 'Sensor-based food waste tracking in UIU canteen with predictive ordering suggestions.'],
            ['id' => 4, 'user_id' => 1, 'title' => 'Federated Learning for Hospital Networks', 'upvotes_count' => 87, 'comments_count' => 22, 'domain' => 'AI / Healthcare', 'skills' => ['Federated Learning', 'Privacy', 'Python'], 'status' => 'Open', 'description' => 'Train ML models across multiple hospitals without sharing patient data — preserving privacy while improving accuracy.'],
            ['id' => 5, 'user_id' => 2, 'title' => 'Student Mental Health Chatbot for UIU', 'upvotes_count' => 145, 'comments_count' => 35, 'domain' => 'NLP / Mental Health', 'skills' => ['NLP', 'Chatbot', 'Psychology'], 'status' => 'Open', 'description' => 'An empathetic AI chatbot specifically designed for UIU students to provide mental health support and resource guidance.'],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO ideas (id, user_id, title, description, domain, status, upvotes_count, comments_count)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE title=VALUES(title), upvotes_count=VALUES(upvotes_count)
        ");

        $skillStmt = $this->pdo->prepare("INSERT INTO idea_skills (idea_id, skill) VALUES (?, ?)");

        foreach ($ideas as $i) {
            $stmt->execute([
                $i['id'], $i['user_id'], $i['title'], $i['description'],
                $i['domain'], $i['status'], $i['upvotes_count'], $i['comments_count']
            ]);

            foreach ($i['skills'] as $s) {
                $skillStmt->execute([$i['id'], $s]);
            }
        }
    }
}
