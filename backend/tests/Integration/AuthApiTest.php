<?php

namespace Tests\Integration;

use App\Database\Connection;
use App\Middleware\CorsMiddleware;
use App\Middleware\ErrorHandlerMiddleware;
use App\Utils\JWT;
use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;

class AuthApiTest extends TestCase
{
    private $app;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = Connection::getInstance();
        $this->app = AppFactory::create();
        $this->app->addBodyParsingMiddleware();
        $this->app->addRoutingMiddleware();
        $this->app->add(new CorsMiddleware());
        $this->app->add(new ErrorHandlerMiddleware());

        $routes = require __DIR__ . '/../../routes/api.php';
        $routes($this->app);
    }

    public function testLoginSuccessEndpoint(): void
    {
        $body = json_encode([
            'email' => 'rafsan.ahmed@uiu.ac.bd',
            'password' => 'password123'
        ]);

        $stream = (new StreamFactory())->createStream($body);
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/auth/login')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);

        $response = $this->app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());

        $payload = json_decode((string) $response->getBody(), true);
        $this->assertTrue($payload['success']);
        $this->assertNotEmpty($payload['data']['token']);
        $this->assertEquals('Rafsan Ahmed', $payload['data']['user']['name']);
    }

    public function testLoginInvalidPasswordEndpoint(): void
    {
        $body = json_encode([
            'email' => 'rafsan.ahmed@uiu.ac.bd',
            'password' => 'wrongpass'
        ]);

        $stream = (new StreamFactory())->createStream($body);
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/auth/login')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);

        $response = $this->app->handle($request);
        $this->assertEquals(401, $response->getStatusCode());

        $payload = json_decode((string) $response->getBody(), true);
        $this->assertFalse($payload['success']);
    }

    public function testGetMeWithValidToken(): void
    {
        // Issue token for user ID 1
        $token = JWT::encode(['sub' => 1, 'email' => 'rafsan.ahmed@uiu.ac.bd', 'role' => 'Researcher'], 3600);

        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/api/auth/me')
            ->withHeader('Authorization', 'Bearer ' . $token);

        $response = $this->app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());

        $payload = json_decode((string) $response->getBody(), true);
        $this->assertTrue($payload['success']);
        $this->assertEquals(1, $payload['data']['id']);
    }

    public function testGetMeFailsWithoutToken(): void
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/api/auth/me');

        $response = $this->app->handle($request);
        $this->assertEquals(401, $response->getStatusCode());
    }
}
