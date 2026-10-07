<?php

namespace Tests\Unit;

use App\Database\Connection;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    protected function setUp(): void
    {
        $pdo = Connection::getInstance();
        $this->userService = new UserService($pdo);
    }

    public function testGetProfile(): void
    {
        $profile = $this->userService->getProfile(1);
        $this->assertEquals('Rafsan Ahmed', $profile['name']);
        $this->assertIsArray($profile['skills']);
        $this->assertContains('Python', $profile['skills']);
    }

    public function testGetLeaderboard(): void
    {
        $leaderboard = $this->userService->getLeaderboard(5);
        $this->assertCount(5, $leaderboard);
        $this->assertEquals(1, $leaderboard[0]['rank']);
    }

    public function testSearchUsers(): void
    {
        $results = $this->userService->search('CSE');
        $this->assertNotEmpty($results['data']);
    }
}
