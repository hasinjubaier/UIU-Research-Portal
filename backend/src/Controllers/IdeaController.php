<?php

namespace App\Controllers;

use App\Services\IdeaService;
use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IdeaController extends BaseController
{
    private IdeaService $ideaService;

    public function __construct()
    {
        parent::__construct();
        $this->ideaService = new IdeaService($this->db);
    }

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->getQueryParams($request);
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = min(100, (int)($params['per_page'] ?? 20));

        $filters = [];
        if (!empty($params['domain'])) {
            $filters['domain'] = $params['domain'];
        }
        if (!empty($params['status'])) {
            $filters['status'] = $params['status'];
        }
        if (!empty($params['q'])) {
            $filters['query'] = $params['q'];
        }

        $result = $this->ideaService->list($page, $perPage, $filters);
        return $this->json($response, $result['data'], 200, '', $result['pagination']);
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $idea = $this->ideaService->get($id);
        return $this->json($response, $idea, 200);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->getBody($request);
        $userId = $this->getUserId($request);

        Validator::make($body, [
            'title'       => 'required|min:5',
            'description' => 'required',
        ])->validate();

        $created = $this->ideaService->create($body, $userId);
        return $this->json($response, $created, 201, 'Idea posted successfully');
    }

    public function toggleUpvote(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $result = $this->ideaService->toggleUpvote($id, $userId);
        return $this->json($response, $result, 200);
    }

    public function addComment(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);
        $body = $this->getBody($request);

        Validator::make($body, [
            'comment' => 'required',
        ])->validate();

        $this->ideaService->addComment($id, $userId, $body['comment']);
        return $this->json($response, ['commented' => true], 201, 'Comment added');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $this->ideaService->delete($id, $userId);
        return $this->json($response, ['deleted' => true], 200, 'Idea deleted');
    }
}
