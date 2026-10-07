<?php

namespace App\Controllers;

use App\Services\MessageService;
use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MessageController extends BaseController
{
    private MessageService $messageService;

    public function __construct()
    {
        parent::__construct();
        $this->messageService = new MessageService($this->db);
    }

    public function listConversations(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $conversations = $this->messageService->getUserConversations($userId);
        return $this->json($response, $conversations, 200);
    }

    public function getMessages(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $convId = (int)$args['id'];
        $userId = $this->getUserId($request);
        $params = $this->getQueryParams($request);
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = min(100, (int)($params['per_page'] ?? 50));

        $messages = $this->messageService->getConversationMessages($convId, $userId, $page, $perPage);
        return $this->json($response, $messages, 200);
    }

    public function sendMessage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $convId = (int)$args['id'];
        $userId = $this->getUserId($request);
        $body = $this->getBody($request);

        Validator::make($body, [
            'message' => 'required',
        ])->validate();

        $message = $this->messageService->sendMessage($convId, $userId, $body['message']);
        return $this->json($response, $message, 201, 'Message sent');
    }

    public function startDm(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $body = $this->getBody($request);

        Validator::make($body, [
            'user_id' => 'required|numeric',
        ])->validate();

        $otherId = (int)$body['user_id'];
        $conv = $this->messageService->startDm($userId, $otherId);

        return $this->json($response, $conv, 200);
    }

    public function markRead(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $convId = (int)$args['id'];
        $userId = $this->getUserId($request);

        $this->messageService->markAsRead($convId, $userId);
        return $this->json($response, ['marked_read' => true], 200);
    }
}
