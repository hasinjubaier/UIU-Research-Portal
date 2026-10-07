<?php

namespace Tests\Unit;

use App\Database\Connection;
use App\Services\AuthService;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;

    protected function setUp(): void
    {
        $pdo = Connection::getInstance();
        $this->authService = new AuthService($pdo);
    }

    public function testLoginSuccess(): void
    {
        $result = $this->authService->login('rafsan.ahmed@uiu.ac.bd', 'password123');

        $this->assertNotEmpty($result['token']);
        $this->assertEquals('Rafsan Ahmed', $result['user']['name']);
        $this->assertEquals('rafsan.ahmed@uiu.ac.bd', $result['user']['email']);
    }

    public function testLoginFailsWithInvalidPassword(): void
    {
        $this->expectException(\App\Exceptions\AuthenticationException::class);
        $this->authService->login('rafsan.ahmed@uiu.ac.bd', 'wrongpassword');
    }

    public function testLoginFailsWithNonexistentUser(): void
    {
        $this->expectException(\App\Exceptions\AuthenticationException::class);
        $this->authService->login('nobody@uiu.ac.bd', 'password123');
    }
}
