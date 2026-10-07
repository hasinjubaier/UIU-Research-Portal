<?php

namespace App\Controllers;

use App\Services\ReputationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ReputationController extends BaseController
{
    private ReputationService $reputationService;

    public function __construct()
    {
        parent::__construct();
        $this->reputationService = new ReputationService($this->db);
    }

    public function getBreakdown(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $breakdown = $this->reputationService->getReputationBreakdown($userId);
        return $this->json($response, $breakdown, 200);
    }

    public function getBadges(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $badges = $this->reputationService->getAllBadges($userId);
        return $this->json($response, $badges, 200);
    }

    public function awardBadge(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $badgeId = (int)$args['badgeId'];
        $body = $this->getBody($request);
        $userId = (int)($body['user_id'] ?? $this->getUserId($request));

        $this->reputationService->awardBadge($userId, $badgeId);
        return $this->json($response, ['awarded' => true], 200, 'Badge awarded');
    }
}
