<?php

namespace App\Middleware;

use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationMiddleware implements MiddlewareInterface
{
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $body = (array)($request->getParsedBody() ?? []);
        $validator = Validator::make($body, $this->rules);
        $validated = $validator->validate();

        $request = $request->withAttribute('validated', $validated);

        return $handler->handle($request);
    }
}
