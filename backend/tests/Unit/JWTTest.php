<?php

namespace Tests\Unit;

use App\Utils\JWT;
use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase
{
    public function testEncodeAndDecode(): void
    {
        $payload = ['sub' => 42, 'role' => 'Student'];
        $token = JWT::encode($payload, 3600);

        $this->assertNotEmpty($token);
        $decoded = JWT::decode($token);

        $this->assertNotNull($decoded);
        $this->assertEquals(42, $decoded['sub']);
        $this->assertEquals('Student', $decoded['role']);
    }

    public function testTamperedTokenReturnsNull(): void
    {
        $token = JWT::encode(['sub' => 1]);
        $tampered = $token . 'tampered';

        $this->assertNull(JWT::decode($tampered));
    }
}
