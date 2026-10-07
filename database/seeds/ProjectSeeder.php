<?php

namespace App\Database\Seeds;

use PDO;

class ProjectSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $projects = [
            [
                'id' => 1,
                'title' => 'AI-Based Healthcare Diagnosis',
                'status' => 'active',
                'description' => 'Leveraging deep learning to assist in early disease detection from medical imaging data.',
                'domain' => 'AI / Healthcare',
                'members' => [1, 7, 5],
                'progress' => 65,
                'deadline' => '2025-06-30',
                'tags' => ['Machine Learning', 'Healthcare', 'Python'],
                'visibility' => 'private',
                'files_count' => 18,
                'milestones_count' => 4,
                'completed_milestones' => 2,
                'created_by' => 1
            ],
            [
                'id' => 2,
                'title' => 'Smart Campus Navigation App',
                'status' => 'active',
                'description' => 'Indoor navigation system for UIU campus using BLE beacons and AR overlays.',
                'domain' => 'Mobile / IoT',
                'members' => [3, 2, 8],
                'progress' => 40,
                'deadline' => '2025-07-15',
                'tags' => ['React Native', 'IoT', 'AR'],
                'visibility' => 'public',
                'files_count' => 11,
                'milestones_count' => 3,
                'completed_milestones' => 1,
                'created_by' => 3
            ],
            [
                'id' => 3,
                'title' => 'Blockchain-Based Credential Verification',
                'status' => 'active',
                'description' => 'Tamper-proof academic credential system using Ethereum smart contracts.',
                'domain' => 'Blockchain',
                'members' => [8, 4, 1],
                'progress' => 30,
                'deadline' => '2025-08-01',
                'tags' => ['Blockchain', 'Solidity', 'Web3'],
                'visibility' => 'public',
                'files_count' => 7,
                'milestones_count' => 5,
                'completed_milestones' => 1,
                'created_by' => 8
            ],
            [
                'id' => 4,
                'title' => 'NLP-Powered Research Summarizer',
                'status' => 'completed',
                'description' => 'Automated academic paper summarization using transformer models.',
                'domain' => 'NLP / AI',
                'members' => [1, 5, 7],
                'progress' => 100,
                'deadline' => '2025-03-01',
                'tags' => ['NLP', 'BERT', 'Python'],
                'visibility' => 'public',
                'files_count' => 26,
                'milestones_count' => 4,
                'completed_milestones' => 4,
                'created_by' => 1
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO projects (id, title, description, domain, status, progress, deadline, visibility, files_count, milestones_count, completed_milestones, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE title=VALUES(title), progress=VALUES(progress), status=VALUES(status)
        ");

        $tagStmt = $this->pdo->prepare("INSERT INTO project_tags (project_id, tag) VALUES (?, ?)");
        $memberStmt = $this->pdo->prepare("
            INSERT INTO project_members (project_id, user_id, role)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE role=VALUES(role)
        ");

        foreach ($projects as $p) {
            $stmt->execute([
                $p['id'], $p['title'], $p['description'], $p['domain'], $p['status'],
                $p['progress'], $p['deadline'], $p['visibility'], $p['files_count'],
                $p['milestones_count'], $p['completed_milestones'], $p['created_by']
            ]);

            foreach ($p['tags'] as $t) {
                $tagStmt->execute([$p['id'], $t]);
            }

            foreach ($p['members'] as $idx => $mid) {
                $role = ($mid === $p['created_by']) ? 'lead' : 'contributor';
                $memberStmt->execute([$p['id'], $mid, $role]);
            }
        }
    }
}
