<?php

namespace App\Database\Seeds;

use PDO;

class ResourceSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $resources = [
            ['id' => 1, 'title' => 'Deep Learning for Medical Image Analysis', 'type' => 'Paper', 'category' => 'AI/ML', 'user_id' => 1, 'author_name' => 'Rafsan Ahmed', 'downloads_count' => 142, 'rating' => 4.8, 'file_size' => '2.4 MB', 'tags' => ['CNN', 'Healthcare', 'PyTorch']],
            ['id' => 2, 'title' => 'Introduction to Transformer Models', 'type' => 'Study Material', 'category' => 'NLP', 'user_id' => 2, 'author_name' => 'Nusrat Jahan', 'downloads_count' => 318, 'rating' => 4.9, 'file_size' => '1.8 MB', 'tags' => ['NLP', 'BERT', 'GPT']],
            ['id' => 3, 'title' => 'Smart Grid Optimization Techniques', 'type' => 'PDF', 'category' => 'EEE', 'user_id' => 6, 'author_name' => 'Farhan Kabir', 'downloads_count' => 89, 'rating' => 4.5, 'file_size' => '3.1 MB', 'tags' => ['IoT', 'Power Systems']],
            ['id' => 4, 'title' => 'Blockchain Fundamentals & Use Cases', 'type' => 'Study Material', 'category' => 'CS', 'user_id' => 8, 'author_name' => 'Imran Chowdhury', 'downloads_count' => 205, 'rating' => 4.7, 'file_size' => '4.2 MB', 'tags' => ['Blockchain', 'Web3', 'DeFi']],
            ['id' => 5, 'title' => 'React 18 Best Practices Guide', 'type' => 'Tutorial', 'category' => 'Web Dev', 'user_id' => 3, 'author_name' => 'Tanvir Hossain', 'downloads_count' => 456, 'rating' => 4.9, 'file_size' => '1.2 MB', 'tags' => ['React', 'JavaScript', 'Frontend']],
            ['id' => 6, 'title' => 'Statistical Methods in Research', 'type' => 'PDF', 'category' => 'Statistics', 'user_id' => 4, 'author_name' => 'Sadia Islam', 'downloads_count' => 167, 'rating' => 4.6, 'file_size' => '5.7 MB', 'tags' => ['Statistics', 'Research Methods', 'SPSS']],
            // Datasets
            ['id' => 7, 'title' => 'UIU Campus Air Quality Dataset 2024', 'type' => 'Dataset', 'category' => 'Environment', 'user_id' => 7, 'author_name' => 'Maliha Rahman', 'downloads_count' => 78, 'rating' => 4.6, 'file_size' => '45 MB', 'tags' => ['IoT', 'Environment', 'CSV'], 'description' => 'Hourly air quality readings from 12 sensors across UIU campus for 2024.', 'stars_count' => 23, 'license' => 'CC BY 4.0', 'version' => 'v2.1'],
            ['id' => 8, 'title' => 'Chest X-Ray Preprocessing Scripts', 'type' => 'Code', 'category' => 'Medical Imaging', 'user_id' => 1, 'author_name' => 'Rafsan Ahmed', 'downloads_count' => 156, 'rating' => 4.8, 'file_size' => '1.2 MB', 'tags' => ['Python', 'Medical Imaging', 'DICOM'], 'description' => 'Python scripts to preprocess and augment chest X-ray images from NIH dataset.', 'stars_count' => 41, 'license' => 'MIT', 'version' => 'v1.3'],
            ['id' => 9, 'title' => 'Bangla NLP Text Corpus (UIU Edition)', 'type' => 'Dataset', 'category' => 'NLP', 'user_id' => 2, 'author_name' => 'Nusrat Jahan', 'downloads_count' => 234, 'rating' => 4.9, 'file_size' => '120 MB', 'tags' => ['NLP', 'Bengali', 'Text'], 'description' => 'Curated Bangla text corpus from news, social media, and academic sources.', 'stars_count' => 67, 'license' => 'Research Only', 'version' => 'v3.0'],
            ['id' => 10, 'title' => 'Smart Grid Simulation Results', 'type' => 'Experiment', 'category' => 'EEE', 'user_id' => 6, 'author_name' => 'Farhan Kabir', 'downloads_count' => 45, 'rating' => 4.4, 'file_size' => '22 MB', 'tags' => ['MATLAB', 'Power Systems', 'Simulation'], 'description' => 'MATLAB Simulink results for grid optimization experiments.', 'stars_count' => 15, 'license' => 'CC BY-SA 4.0', 'version' => 'v1.0'],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO resources (id, title, description, type, category, user_id, author_name, file_size, downloads_count, rating, license, version, stars_count)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE title=VALUES(title), downloads_count=VALUES(downloads_count)
        ");

        $tagStmt = $this->pdo->prepare("INSERT INTO resource_tags (resource_id, tag) VALUES (?, ?)");

        foreach ($resources as $r) {
            $desc = $r['description'] ?? "Resource shared by academic collaborator {$r['author_name']}";
            $lic = $r['license'] ?? 'MIT';
            $ver = $r['version'] ?? 'v1.0';
            $stars = $r['stars_count'] ?? 0;

            $stmt->execute([
                $r['id'], $r['title'], $desc, $r['type'], $r['category'],
                $r['user_id'], $r['author_name'], $r['file_size'], $r['downloads_count'],
                $r['rating'], $lic, $ver, $stars
            ]);

            foreach ($r['tags'] as $t) {
                $tagStmt->execute([$r['id'], $t]);
            }
        }
    }
}
