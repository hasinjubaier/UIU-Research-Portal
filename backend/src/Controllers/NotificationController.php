<?php

namespace App\Controllers;

use App\Services\NotificationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotificationController extends BaseController
{
    private NotificationService $notifService;

    public function __construct()
    {
        parent::__construct();
        $this->notifService = new NotificationService($this->db);
    }

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $params = $this->getQueryParams($request);
        $limit = min(50, (int)($params['limit'] ?? 20));

        $notifications = $this->notifService->getForUser($userId, $limit);
        return $this->json($response, $notifications, 200);
    }

    public function markRead(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $this->notifService->markAsRead($id, $userId);
        return $this->json($response, ['marked_read' => true], 200);
    }

    public function markAllRead(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $this->notifService->markAllAsRead($userId);
        return $this->json($response, ['all_marked_read' => true], 200);
    }
}
