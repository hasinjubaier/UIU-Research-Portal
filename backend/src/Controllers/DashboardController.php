<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Services\AnalyticsService;
use App\Services\NotificationService;
use App\Services\SearchService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DashboardController extends BaseController
{
    private AnalyticsService $analyticsService;
    private NotificationService $notifService;
    private Project $projectModel;
    private User $userModel;
    private SearchService $searchService;

    public function __construct()
    {
        parent::__construct();
        $this->analyticsService = new AnalyticsService($this->db);
        $this->notifService = new NotificationService($this->db);
        $this->projectModel = new Project($this->db);
        $this->userModel = new User($this->db);
        $this->searchService = new SearchService($this->db);
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getUserId($request);

        $currentUser = $this->userModel->getFullProfile($userId);
        $stats = $this->analyticsService->getDashboardStats($userId);
        $recentProjects = $this->projectModel->listWithDetails(1, 4, ['user_id' => $userId]);
        $notifications = $this->notifService->getForUser($userId, 5);
        $leaderboard = $this->userModel->getLeaderboard(5);

        $payload = [
            'currentUser'    => $currentUser,
            'stats'          => $stats,
            'recentProjects' => $recentProjects['data'],
            'notifications'  => $notifications,
            'leaderboard'    => $leaderboard,
        ];

        return $this->json($response, $payload, 200);
    }

    public function search(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->getQueryParams($request);
        $query = trim($params['q'] ?? '');

        if ($query === '') {
            return $this->json($response, [
                'projects'  => [],
                'resources' => [],
                'users'     => [],
                'ideas'     => [],
                'total'     => 0
            ], 200);
        }

        $results = $this->searchService->searchAll($query, 10);
        return $this->json($response, $results, 200);
    }
}
