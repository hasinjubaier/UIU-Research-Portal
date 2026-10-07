<?php

namespace App\Middleware;

use App\Exceptions\AuthorizationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthorizationMiddleware implements MiddlewareInterface
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles = ['admin'])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            throw new AuthorizationException('Authentication required for authorization check', 401);
        }

        $userRole = strtolower($user['role'] ?? '');
        $allowed = array_map('strtolower', $this->allowedRoles);

        if (!in_array($userRole, $allowed) && !in_array('*', $allowed)) {
            throw new AuthorizationException(
                "Access denied. Requires one of the following roles: " . implode(', ', $this->allowedRoles),
                403
            );
        }

        return $handler->handle($request);
    }
}
