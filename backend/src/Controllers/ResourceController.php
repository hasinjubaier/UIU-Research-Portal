<?php

namespace App\Controllers;

use App\Services\ResourceService;
use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ResourceController extends BaseController
{
    private ResourceService $resourceService;

    public function __construct()
    {
        parent::__construct();
        $this->resourceService = new ResourceService($this->db);
    }

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->getQueryParams($request);
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = min(100, (int)($params['per_page'] ?? 20));

        $filters = [];
        if (!empty($params['type'])) {
            $filters['type'] = $params['type'];
        }
        if (!empty($params['category'])) {
            $filters['category'] = $params['category'];
        }
        if (!empty($params['is_dataset'])) {
            $filters['is_dataset'] = true;
        }
        if (!empty($params['q'])) {
            $filters['query'] = $params['q'];
        }

        $result = $this->resourceService->list($page, $perPage, $filters);
        return $this->json($response, $result['data'], 200, '', $result['pagination']);
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $resource = $this->resourceService->get($id);
        return $this->json($response, $resource, 200);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->getBody($request);
        $userId = $this->getUserId($request);

        Validator::make($body, [
            'title' => 'required|min:3',
            'type'  => 'required',
        ])->validate();

        $uploadedFiles = $request->getUploadedFiles();
        $file = $uploadedFiles['file'] ?? null;

        $created = $this->resourceService->create($body, $file, $userId);
        return $this->json($response, $created, 201, 'Resource published successfully');
    }

    public function download(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $resource = $this->resourceService->download($id, $userId);
        return $this->json($response, ['downloaded' => true, 'downloads' => $resource['downloads_count']], 200);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $this->resourceService->delete($id, $userId);
        return $this->json($response, ['deleted' => true], 200, 'Resource deleted successfully');
    }
}
