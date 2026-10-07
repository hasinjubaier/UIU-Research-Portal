<?php

namespace App\Controllers;

use App\Services\EventService;
use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EventController extends BaseController
{
    private EventService $eventService;

    public function __construct()
    {
        parent::__construct();
        $this->eventService = new EventService($this->db);
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
        if (!empty($params['status'])) {
            $filters['status'] = $params['status'];
        }
        if (!empty($params['q'])) {
            $filters['query'] = $params['q'];
        }

        $result = $this->eventService->list($page, $perPage, $filters);
        return $this->json($response, $result['data'], 200, '', $result['pagination']);
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $event = $this->eventService->get($id);
        return $this->json($response, $event, 200);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->getBody($request);
        $userId = $this->getUserId($request);

        Validator::make($body, [
            'title'      => 'required|min:3',
            'type'       => 'required',
            'start_date' => 'required',
        ])->validate();

        $created = $this->eventService->create($body, $userId);
        return $this->json($response, $created, 201, 'Event created successfully');
    }

    public function register(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $this->eventService->register($id, $userId);
        return $this->json($response, ['registered' => true], 200, 'Successfully registered for event');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $userId = $this->getUserId($request);

        $this->eventService->delete($id, $userId);
        return $this->json($response, ['deleted' => true], 200, 'Event deleted');
    }
}
