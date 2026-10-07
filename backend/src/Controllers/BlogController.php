<?php

namespace App\Controllers;

use App\Services\BlogService;
use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlogController extends BaseController
{
    private BlogService $blogService;

    public function __construct()
    {
        parent::__construct();
        $this->blogService = new BlogService($this->db);
    }

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->getQueryParams($request);
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = min(100, (int)($params['per_page'] ?? 20));

        $filters = [];
        if (!empty($params['category'])) {
            $filters['category'] = $params['category'];
        }
        if (!empty($params['q'])) {
            $filters['query'] = $params['q'];
        }

        $result = $this->blogService->list($page, $perPage, $filters);
        return $this->json($response, $result['data'], 200, '', $result['pagination']);
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $post = $this->blogService->get($id);
        return $this->json($response, $post, 200);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->getBody($request);
        $userId = $this->getUserId($request);

        Validator::make($body, [
            'title'   => 'required|min:5',
            'content' => 'required',
        ])->validate();

        $created = $this->blogService->create($body, $userId);
        return $this->json($response, $created, 201, 'Blog post published');
    }

    public function toggleLike(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $postId = (int)$args['id'];
        $userId = $this->getUserId($request);

        $result = $this->blogService->toggleLike($postId, $userId);
        return $this->json($response, $result, 200);
    }

    public function addComment(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $postId = (int)$args['id'];
        $userId = $this->getUserId($request);
        $body = $this->getBody($request);

        Validator::make($body, [
            'comment' => 'required',
        ])->validate();

        $comment = $this->blogService->addComment($postId, $userId, $body['comment'], $body['parent_id'] ?? null);
        return $this->json($response, $comment, 201, 'Comment added');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $this->blogService->delete($id, $userId);
        return $this->json($response, ['deleted' => true], 200, 'Blog post deleted');
    }
}
