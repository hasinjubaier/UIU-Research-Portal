<?php

namespace App\Middleware;

use App\Exceptions\AuthenticationException;
use App\Exceptions\AuthorizationException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ValidationException;
use App\Utils\Logger;
use App\Utils\Response as ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Psr7\Response;
use Throwable;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            $response = new Response();
            return ApiResponse::error(
                $response,
                $e->getMessage(),
                422,
                $e->getErrors()
            );
        } catch (AuthenticationException $e) {
            $response = new Response();
            return ApiResponse::error($response, $e->getMessage(), 401);
        } catch (AuthorizationException $e) {
            $response = new Response();
            return ApiResponse::error($response, $e->getMessage(), 403);
        } catch (ResourceNotFoundException $e) {
            $response = new Response();
            return ApiResponse::error($response, $e->getMessage(), 404);
        } catch (HttpNotFoundException $e) {
            $response = new Response();
            return ApiResponse::error($response, 'API endpoint not found', 404);
        } catch (HttpMethodNotAllowedException $e) {
            $response = new Response();
            return ApiResponse::error($response, 'HTTP method not allowed for this endpoint', 405);
        } catch (Throwable $e) {
            Logger::error('Unhandled server exception: ' . $e->getMessage(), $e);

            $settings = require __DIR__ . '/../../config/settings.php';
            $debug = $settings['app']['debug'] ?? false;

            $response = new Response();
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? (int)$e->getCode() : 500;
            $details = $debug ? [
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => explode("\n", $e->getTraceAsString()),
            ] : null;

            return ApiResponse::error(
                $response,
                $debug ? $e->getMessage() : 'An unexpected internal server error occurred',
                $statusCode,
                $details
            );
        }
    }
}
