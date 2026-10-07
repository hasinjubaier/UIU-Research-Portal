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

class ProjectApiTest extends TestCase
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

    public function testGetProjectsListEndpoint(): void
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/api/projects');

        $response = $this->app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());

        $payload = json_decode((string) $response->getBody(), true);
        $this->assertTrue($payload['success']);
        $this->assertIsArray($payload['data']);
        $this->assertGreaterThan(0, count($payload['data']));
    }

    public function testGetProjectDetailEndpoint(): void
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/api/projects/1');

        $response = $this->app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());

        $payload = json_decode((string) $response->getBody(), true);
        $this->assertTrue($payload['success']);
        $this->assertEquals(1, $payload['data']['id']);
        $this->assertNotEmpty($payload['data']['title']);
    }

    public function testCreateProjectFailsWithoutAuth(): void
    {
        $body = json_encode([
            'title' => 'Unauthorized Research Project',
            'description' => 'Test description'
        ]);

        $stream = (new StreamFactory())->createStream($body);
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/projects')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);

        $response = $this->app->handle($request);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testCreateProjectSuccessWithAuth(): void
    {
        $token = JWT::encode(['sub' => 1, 'email' => 'rafsan.ahmed@uiu.ac.bd', 'role' => 'Researcher'], 3600);

        $body = json_encode([
            'title' => 'Automated Integration Test Project ' . time(),
            'description' => 'Testing project creation from integration test suite',
            'department' => 'CSE',
            'tags' => ['AI', 'Testing'],
            'maxMembers' => 4
        ]);

        $stream = (new StreamFactory())->createStream($body);
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/projects')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->withBody($stream);

        $response = $this->app->handle($request);
        $this->assertEquals(201, $response->getStatusCode());

        $payload = json_decode((string) $response->getBody(), true);
        $this->assertTrue($payload['success']);
        $this->assertNotEmpty($payload['data']['id']);
    }
}
