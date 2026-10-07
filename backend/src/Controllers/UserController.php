<?php

namespace App\Controllers;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService($this->db);
    }

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->getQueryParams($request);
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = min(100, (int)($params['per_page'] ?? 20));
        $query = $params['q'] ?? '';

        $filters = [];
        if (!empty($params['department'])) {
            $filters['department'] = $params['department'];
        }
        if (!empty($params['year'])) {
            $filters['year'] = $params['year'];
        }

        $result = $this->userService->search($query, $filters, $page, $perPage);
        return $this->json($response, $result['data'], 200, '', $result['pagination']);
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $user = $this->userService->getProfile($id);
        return $this->json($response, $user, 200);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $authId = $this->getUserId($request);

        if ($authId !== $id) {
            return $this->error($response, 'You can only update your own profile', 403);
        }

        $body = $this->getBody($request);
        $updated = $this->userService->updateProfile($id, $body);

        return $this->json($response, $updated, 200, 'Profile updated successfully');
    }

    public function leaderboard(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->getQueryParams($request);
        $limit = min(50, (int)($params['limit'] ?? 10));

        $leaderboard = $this->userService->getLeaderboard($limit);
        return $this->json($response, $leaderboard, 200);
    }
}
