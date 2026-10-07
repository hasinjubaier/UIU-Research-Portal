<?php

namespace App\Database\Seeds;

use PDO;

class UserSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $password = password_hash('password123', PASSWORD_DEFAULT);

        $users = [
            [
                'id' => 1,
                'name' => 'Rafsan Ahmed',
                'email' => 'rafsan.ahmed@uiu.ac.bd',
                'password_hash' => $password,
                'avatar' => null,
                'initials' => 'RA',
                'department' => 'Computer Science & Engineering',
                'academic_year' => '3rd Year',
                'role' => 'Graduate Student',
                'bio' => 'Passionate about AI, machine learning, and healthcare applications. Looking for collaborative minds!',
                'reputation' => 2840,
                'status' => 'online',
                'skills' => ['Python', 'Machine Learning', 'TensorFlow', 'React', 'Data Analysis'],
                'interests' => ['AI in Healthcare', 'Computer Vision', 'NLP', 'Edge Computing'],
                'looking_for' => ['Frontend developers', 'Research collaborators']
            ],
            [
                'id' => 2,
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat.jahan@uiu.ac.bd',
                'password_hash' => $password,
                'avatar' => null,
                'initials' => 'NJ',
                'department' => 'EEE',
                'academic_year' => '4th Year',
                'role' => 'Student',
                'bio' => 'Focused on smart grids, embedded systems, and VLSI circuit design.',
                'reputation' => 3200,
                'status' => 'online',
                'skills' => ['VLSI', 'IoT', 'Arduino', 'Signal Processing'],
                'interests' => ['Smart Grid', 'Embedded Systems'],
                'looking_for' => ['IoT experts', 'Data analysts']
            ],
            [
                'id' => 3,
                'name' => 'Tanvir Hossain',
                'email' => 'tanvir.hossain@uiu.ac.bd',
                'password_hash' => $password,
                'avatar' => null,
                'initials' => 'TH',
                'department' => 'CSE',
                'academic_year' => '2nd Year',
                'role' => 'Student',
                'bio' => 'Full-stack web developer and open source enthusiast.',
                'reputation' => 1850,
                'status' => 'offline',
                'skills' => ['React', 'Node.js', 'MongoDB', 'TypeScript'],
                'interests' => ['Web Dev', 'System Design'],
                'looking_for' => ['Backend devs', 'UI designers']
            ],
            [
                'id' => 4,
                'name' => 'Sadia Islam',
                'email' => 'sadia.islam@uiu.ac.bd',
                'password_hash' => $password,
                'avatar' => null,
                'initials' => 'SI',
                'department' => 'BBA',
                'academic_year' => '3rd Year',
                'role' => 'Student',
                'bio' => 'Exploring the intersection of business strategy, data analytics, and fintech.',
                'reputation' => 2100,
                'status' => 'online',
                'skills' => ['Data Analysis', 'Excel', 'Python', 'Market Research'],
                'interests' => ['Fintech', 'Business Analytics'],
                'looking_for' => ['CSE students', 'Statisticians']
            ],
            [
                'id' => 5,
                'name' => 'Rakibul Islam',
                'email' => 'rakibul.islam@uiu.ac.bd',
                'password_hash' => $password,
                'avatar' => null,
                'initials' => 'RI',
                'department' => 'CSE',
                'academic_year' => '4th Year',
                'role' => 'Student',
                'bio' => 'Computer vision researcher working on autonomous systems and robotics.',
                'reputation' => 4100,
                'status' => 'online',
                'skills' => ['AI', 'PyTorch', 'Computer Vision', 'C++'],
                'interests' => ['Autonomous Systems', 'Robotics'],
                'looking_for' => ['ML engineers', 'Robotics enthusiasts']
            ],
            [
                'id' => 6,
                'name' => 'Farhan Kabir',
                'email' => 'farhan.kabir@uiu.ac.bd',
                'password_hash' => $password,
                'avatar' => null,
                'initials' => 'FK',
                'department' => 'ME',
                'academic_year' => '3rd Year',
                'role' => 'Student',
                'bio' => 'Mechanical engineering student passionate about renewable energy and simulations.',
                'reputation' => 1650,
                'status' => 'offline',
                'skills' => ['CAD', 'MATLAB', 'Thermal Analysis', 'FEM'],
                'interests' => ['Renewable Energy', 'Heat Transfer'],
                'looking_for' => ['CSE collaborators', 'EEE students']
            ],
            [
                'id' => 7,
                'name' => 'Maliha Rahman',
                'email' => 'maliha.rahman@uiu.ac.bd',
                'password_hash' => $password,
                'avatar' => null,
                'initials' => 'MR',
                'department' => 'CSE',
                'academic_year' => '2nd Year',
                'role' => 'Student',
                'bio' => 'Bioinformatics and data science student analyzing medical and environmental trends.',
                'reputation' => 2300,
                'status' => 'online',
                'skills' => ['Python', 'Data Science', 'SQL', 'Tableau'],
                'interests' => ['Healthcare Data', 'Bioinformatics'],
                'looking_for' => ['Medical students', 'ML experts']
            ],
            [
                'id' => 8,
                'name' => 'Imran Chowdhury',
                'email' => 'imran.chowdhury@uiu.ac.bd',
                'password_hash' => $password,
                'avatar' => null,
                'initials' => 'IC',
                'department' => 'CSE',
                'academic_year' => '4th Year',
                'role' => 'Student',
                'bio' => 'Smart contract developer building decentralized governance and DeFi apps.',
                'reputation' => 2650,
                'status' => 'away',
                'skills' => ['Blockchain', 'Solidity', 'Web3', 'React'],
                'interests' => ['DeFi', 'Smart Contracts'],
                'looking_for' => ['Business analysts', 'Legal experts']
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO users (id, name, email, password_hash, avatar, initials, department, academic_year, role, bio, reputation, status, email_verified_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE name=VALUES(name), reputation=VALUES(reputation), status=VALUES(status)
        ");

        $skillStmt = $this->pdo->prepare("INSERT INTO user_skills (user_id, skill) VALUES (?, ?)");
        $interestStmt = $this->pdo->prepare("INSERT INTO user_interests (user_id, interest) VALUES (?, ?)");
        $lookingStmt = $this->pdo->prepare("INSERT INTO user_looking_for (user_id, looking_for) VALUES (?, ?)");

        foreach ($users as $u) {
            $stmt->execute([
                $u['id'], $u['name'], $u['email'], $u['password_hash'], $u['avatar'],
                $u['initials'], $u['department'], $u['academic_year'], $u['role'],
                $u['bio'], $u['reputation'], $u['status']
            ]);

            foreach ($u['skills'] as $s) {
                $skillStmt->execute([$u['id'], $s]);
            }
            foreach ($u['interests'] as $i) {
                $interestStmt->execute([$u['id'], $i]);
            }
            foreach ($u['looking_for'] as $lf) {
                $lookingStmt->execute([$u['id'], $lf]);
            }
        }
    }
}
