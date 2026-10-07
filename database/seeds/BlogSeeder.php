<?php

namespace App\Database\Seeds;

use PDO;

class BlogSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $blogs = [
            ['id' => 1, 'user_id' => 7, 'title' => 'How We Built a COVID-19 Pattern Analyzer in 3 Weeks', 'category' => 'Research Summary', 'read_time' => '8 min', 'likes_count' => 87, 'comments_count' => 14, 'tags' => ['COVID', 'Data Science', 'Python'], 'excerpt' => "Our team of three tackled an ambitious project: building an end-to-end COVID data analyzer. Here's everything we learned, from data collection to deployment..."],
            ['id' => 2, 'user_id' => 5, 'title' => 'Getting Started with PyTorch for Computer Vision', 'category' => 'Tutorial', 'read_time' => '12 min', 'likes_count' => 134, 'comments_count' => 28, 'tags' => ['PyTorch', 'Computer Vision', 'Tutorial'], 'excerpt' => "Computer vision is one of the most exciting fields in AI. In this tutorial, I'll walk you through building your first image classifier using PyTorch..."],
            ['id' => 3, 'user_id' => 8, 'title' => 'Why Every CS Student Should Learn Blockchain (Even if You\'re Not into Crypto)', 'category' => 'General', 'read_time' => '6 min', 'likes_count' => 52, 'comments_count' => 19, 'tags' => ['Blockchain', 'Web3', 'Career'], 'excerpt' => "Blockchain is far more than Bitcoin. Understanding the underlying technology opens doors to smart contracts, supply chain, healthcare records, and more..."],
            ['id' => 4, 'user_id' => 1, 'title' => 'A Beginner\'s Guide to Writing a Research Paper', 'category' => 'Academic Writing', 'read_time' => '10 min', 'likes_count' => 201, 'comments_count' => 43, 'tags' => ['Research', 'Academic Writing', 'IEEE'], 'excerpt' => "Writing your first research paper is daunting. After struggling through my own first paper, I put together this guide to help fellow students navigate the process..."],
            ['id' => 5, 'user_id' => 3, 'title' => 'Indoor Navigation with BLE Beacons: Lessons Learned', 'category' => 'Research Summary', 'read_time' => '9 min', 'likes_count' => 76, 'comments_count' => 21, 'tags' => ['IoT', 'BLE', 'Mobile'], 'excerpt' => "Building an indoor navigation system sounds straightforward until you encounter beacon interference, multipath effects, and battery constraints..."],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO blog_posts (id, user_id, title, excerpt, content, category, read_time, likes_count, comments_count, published_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE title=VALUES(title), likes_count=VALUES(likes_count)
        ");

        $tagStmt = $this->pdo->prepare("INSERT INTO blog_tags (post_id, tag) VALUES (?, ?)");

        foreach ($blogs as $b) {
            $content = $b['excerpt'] . "\n\nFull article content covering architectural decisions, benchmarks, methodology, experimental findings, and takeaways.";
            $stmt->execute([
                $b['id'], $b['user_id'], $b['title'], $b['excerpt'],
                $content, $b['category'], $b['read_time'], $b['likes_count'], $b['comments_count']
            ]);

            foreach ($b['tags'] as $t) {
                $tagStmt->execute([$b['id'], $t]);
            }
        }
    }
}
