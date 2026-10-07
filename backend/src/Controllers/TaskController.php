<?php

namespace App\Controllers;

use App\Services\TaskService;
use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TaskController extends BaseController
{
    private TaskService $taskService;

    public function __construct()
    {
        parent::__construct();
        $this->taskService = new TaskService($this->db);
    }

    public function listByProject(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $projectId = (int)$args['projectId'];
        $kanban = $this->taskService->getKanban($projectId);
        return $this->json($response, $kanban, 200);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $projectId = (int)$args['projectId'];
        $body = $this->getBody($request);
        $userId = $this->getUserId($request);

        Validator::make($body, [
            'title' => 'required|min:3',
        ])->validate();

        $body['project_id'] = $projectId;
        $task = $this->taskService->create($body, $userId);

        return $this->json($response, $task, 201, 'Task created successfully');
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $body = $this->getBody($request);
        $userId = $this->getUserId($request);

        $task = $this->taskService->update($id, $body, $userId);
        return $this->json($response, $task, 200, 'Task updated successfully');
    }

    public function updateStatus(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $body = $this->getBody($request);

        Validator::make($body, [
            'status' => 'required|in:backlog,todo,in_progress,review,done',
        ])->validate();

        $task = $this->taskService->updateStatus($id, $body['status']);
        return $this->json($response, $task, 200, 'Task status updated');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $this->taskService->delete($id, $userId);
        return $this->json($response, ['deleted' => true], 200, 'Task deleted successfully');
    }
}
