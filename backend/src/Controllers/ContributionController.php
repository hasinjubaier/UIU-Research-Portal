<?php

namespace App\Controllers;

use App\Services\ContributionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ContributionController extends BaseController
{
    private ContributionService $contribService;

    public function __construct()
    {
        parent::__construct();
        $this->contribService = new ContributionService($this->db);
    }

    public function getByProject(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $projectId = (int)$args['projectId'];
        $data = $this->contribService->getByProject($projectId);
        return $this->json($response, $data, 200);
    }
}
