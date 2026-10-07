<?php

namespace Tests\Unit;

use App\Database\Connection;
use App\Services\ProjectService;
use PHPUnit\Framework\TestCase;

class ProjectServiceTest extends TestCase
{
    private ProjectService $projectService;

    protected function setUp(): void
    {
        $pdo = Connection::getInstance();
        $this->projectService = new ProjectService($pdo);
    }

    public function testListProjects(): void
    {
        $projects = $this->projectService->list(1, 10);
        $this->assertNotEmpty($projects['data']);
        $this->assertArrayHasKey('pagination', $projects);
    }

    public function testGetProject(): void
    {
        $project = $this->projectService->get(1);
        $this->assertEquals('AI-Based Healthcare Diagnosis', $project['title']);
        $this->assertArrayHasKey('members', $project);
        $this->assertArrayHasKey('tasks', $project);
    }
}
