<?php

namespace App\Controllers;

use App\Services\ProjectService;
use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProjectController extends BaseController
{
    private ProjectService $projectService;

    public function __construct()
    {
        parent::__construct();
        $this->projectService = new ProjectService($this->db);
    }

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->getQueryParams($request);
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = min(100, (int)($params['per_page'] ?? 20));

        $filters = [];
        if (!empty($params['status'])) {
            $filters['status'] = $params['status'];
        }
        if (!empty($params['visibility'])) {
            $filters['visibility'] = $params['visibility'];
        }
        if (!empty($params['domain'])) {
            $filters['domain'] = $params['domain'];
        }
        if (!empty($params['user_id'])) {
            $filters['user_id'] = (int)$params['user_id'];
        }
        if (!empty($params['q'])) {
            $filters['query'] = $params['q'];
        }

        $result = $this->projectService->list($page, $perPage, $filters);
        return $this->json($response, $result['data'], 200, '', $result['pagination']);
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $project = $this->projectService->get($id);
        return $this->json($response, $project, 200);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->getBody($request);
        $userId = $this->getUserId($request);

        Validator::make($body, [
            'title' => 'required|min:3',
        ])->validate();

        $project = $this->projectService->create($body, $userId);
        return $this->json($response, $project, 201, 'Project created successfully');
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $body = $this->getBody($request);
        $userId = $this->getUserId($request);

        $project = $this->projectService->update($id, $body, $userId);
        return $this->json($response, $project, 200, 'Project updated successfully');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $this->projectService->delete($id, $userId);
        return $this->json($response, ['deleted' => true], 200, 'Project deleted successfully');
    }

    public function addMember(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $body = $this->getBody($request);

        Validator::make($body, [
            'user_id' => 'required|numeric',
        ])->validate();

        $memberId = (int)$body['user_id'];
        $role = $body['role'] ?? 'contributor';

        $this->projectService->addMember($id, $memberId, $role);
        return $this->json($response, ['added' => true], 200, 'Member added to project');
    }

    public function removeMember(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $memberId = (int)$args['userId'];

        $this->projectService->removeMember($id, $memberId);
        return $this->json($response, ['removed' => true], 200, 'Member removed from project');
    }
}
