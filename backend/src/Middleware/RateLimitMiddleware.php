<?php

namespace App\Middleware;

use App\Utils\Response as ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class RateLimitMiddleware implements MiddlewareInterface
{
    private int $maxRequests;
    private int $decaySeconds;

    public function __construct(int $maxRequests = 120, int $decaySeconds = 60)
    {
        $this->maxRequests = $maxRequests;
        $this->decaySeconds = $decaySeconds;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $serverParams = $request->getServerParams();
        $ip = $serverParams['REMOTE_ADDR'] ?? '127.0.0.1';
        $key = md5('ratelimit_' . $ip);

        $cacheDir = __DIR__ . '/../../storage/cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $file = $cacheDir . '/rate_' . $key . '.json';
        $currentTime = time();
        $data = ['count' => 0, 'start' => $currentTime];

        if (file_exists($file)) {
            $content = @file_get_contents($file);
            $parsed = json_decode($content, true);
            if (is_array($parsed)) {
                if ($currentTime - $parsed['start'] < $this->decaySeconds) {
                    $data = $parsed;
                }
            }
        }

        $data['count']++;
        file_put_contents($file, json_encode($data));

        if ($data['count'] > $this->maxRequests) {
            $response = new Response();
            return ApiResponse::error(
                $response,
                'Too many requests. Please slow down.',
                429
            )->withHeader('Retry-After', (string)($this->decaySeconds - ($currentTime - $data['start'])));
        }

        $response = $handler->handle($request);
        return $response
            ->withHeader('X-RateLimit-Limit', (string)$this->maxRequests)
            ->withHeader('X-RateLimit-Remaining', (string)max(0, $this->maxRequests - $data['count']));
    }
}
