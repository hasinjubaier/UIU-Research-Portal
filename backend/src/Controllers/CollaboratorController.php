<?php

namespace App\Controllers;

use App\Services\NotificationService;
use App\Services\UserService;
use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CollaboratorController extends BaseController
{
    private UserService $userService;
    private NotificationService $notifService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService($this->db);
        $this->notifService = new NotificationService($this->db);
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

    public function request(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $body = $this->getBody($request);

        Validator::make($body, [
            'target_user_id' => 'required|numeric',
        ])->validate();

        $targetUserId = (int)$body['target_user_id'];
        $sender = $this->userService->getProfile($userId);

        $message = $body['message'] ?? "{$sender['name']} sent you a collaboration request.";

        $this->notifService->notify(
            $targetUserId,
            'collab',
            $message,
            'collab',
            ['from_user_id' => $userId, 'project_id' => $body['project_id'] ?? null]
        );

        return $this->json($response, ['requested' => true], 200, 'Collaboration request sent');
    }
}
