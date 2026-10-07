<?php

namespace App\Controllers;

use App\Database\Connection;
use App\Utils\Response as ApiResponse;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class BaseController
{
    protected PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Connection::getInstance();
    }

    protected function getUserId(ServerRequestInterface $request): int
    {
        return (int)($request->getAttribute('userId') ?? 0);
    }

    protected function getUser(ServerRequestInterface $request): ?array
    {
        return $request->getAttribute('user');
    }

    protected function getBody(ServerRequestInterface $request): array
    {
        $body = $request->getParsedBody();
        if (is_array($body)) {
            return $body;
        }

        $raw = (string)$request->getBody();
        if (!empty($raw)) {
            $parsed = json_decode($raw, true);
            if (is_array($parsed)) {
                return $parsed;
            }
        }

        return [];
    }

    protected function getQueryParams(ServerRequestInterface $request): array
    {
        return (array)$request->getQueryParams();
    }

    protected function json(
        ResponseInterface $response,
        mixed $data = null,
        int $status = 200,
        string $message = '',
        ?array $pagination = null
    ): ResponseInterface {
        return ApiResponse::json($response, $data, $status, $message, $pagination);
    }

    protected function error(
        ResponseInterface $response,
        string $message = 'Error',
        int $status = 400,
        ?array $details = null
    ): ResponseInterface {
        return ApiResponse::error($response, $message, $status, $details);
    }
}
