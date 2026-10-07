<?php

namespace Tests\Unit;

use App\Database\Connection;
use App\Services\TaskService;
use PHPUnit\Framework\TestCase;

class TaskServiceTest extends TestCase
{
    private TaskService $taskService;

    protected function setUp(): void
    {
        $pdo = Connection::getInstance();
        $this->taskService = new TaskService($pdo);
    }

    public function testGetKanbanBoard(): void
    {
        $kanban = $this->taskService->getKanban(1);

        $this->assertArrayHasKey('todo', $kanban);
        $this->assertArrayHasKey('inProgress', $kanban);
        $this->assertArrayHasKey('done', $kanban);
        $this->assertNotEmpty($kanban['todo']);
    }
}
