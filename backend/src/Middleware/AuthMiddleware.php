<?php

namespace App\Middleware;

use App\Utils\JWT;
use App\Utils\Response as ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class AuthMiddleware implements MiddlewareInterface
{
    private bool $optional;

    public function __construct(bool $optional = false)
    {
        $this->optional = $optional;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authHeader = $request->getHeaderLine('Authorization');
        $token = null;

        if (preg_match('/Bearer\s+(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (!$token) {
            if ($this->optional) {
                return $handler->handle($request);
            }
            $response = new Response();
            return ApiResponse::error($response, 'Authorization token required', 401);
        }

        $decoded = JWT::decode($token);
        if (!$decoded) {
            if ($this->optional) {
                return $handler->handle($request);
            }
            $response = new Response();
            return ApiResponse::error($response, 'Invalid or expired authorization token', 401);
        }

        $userId = $decoded['sub'] ?? $decoded['id'] ?? null;
        $request = $request
            ->withAttribute('user', $decoded)
            ->withAttribute('userId', $userId);

        return $handler->handle($request);
    }
}
