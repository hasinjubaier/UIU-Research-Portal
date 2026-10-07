<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $settings = require __DIR__ . '/../../config/settings.php';
        $cors = $settings['cors'];

        $origin = $request->getHeaderLine('Origin');
        $allowedOrigins = $cors['allowed_origins'] ?? ['*'];

        // Determine allow origin header value
        $allowOrigin = '*';
        if (in_array('*', $allowedOrigins)) {
            $allowOrigin = '*';
        } elseif ($origin && in_array($origin, $allowedOrigins)) {
            $allowOrigin = $origin;
        } elseif (!empty($allowedOrigins)) {
            $allowOrigin = $allowedOrigins[0];
        }

        // Handle OPTIONS preflight request
        if ($request->getMethod() === 'OPTIONS') {
            $response = new Response();
            return $response
                ->withHeader('Access-Control-Allow-Origin', $allowOrigin)
                ->withHeader('Access-Control-Allow-Methods', implode(', ', $cors['allowed_methods']))
                ->withHeader('Access-Control-Allow-Headers', implode(', ', $cors['allowed_headers']))
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Access-Control-Max-Age', '86400')
                ->withStatus(200);
        }

        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', $allowOrigin)
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $cors['allowed_methods']))
            ->withHeader('Access-Control-Allow-Headers', implode(', ', $cors['allowed_headers']))
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}
