<?php

namespace App\Utils;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * Return JSON response
     */
    public static function json(
        ResponseInterface $response,
        mixed $data = null,
        int $status = 200,
        string $message = '',
        ?array $pagination = null
    ): ResponseInterface {
        $payload = [
            'success' => $status >= 200 && $status < 300,
            'status'  => $status,
        ];

        if ($message !== '') {
            $payload['message'] = $message;
        }

        if ($data !== null) {
            $payload['data'] = $data;
        }

        if ($pagination !== null) {
            $payload['pagination'] = $pagination;
        }

        $payload['timestamp'] = date('c');

        $response->getBody()->write((string)json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    /**
     * Return error JSON response
     */
    public static function error(
        ResponseInterface $response,
        string $message = 'An error occurred',
        int $status = 400,
        ?array $details = null
    ): ResponseInterface {
        $payload = [
            'success'   => false,
            'status'    => $status,
            'error'     => [
                'message'   => $message,
                'status'    => $status,
                'timestamp' => date('c'),
            ]
        ];

        if (!empty($details)) {
            $payload['error']['details'] = $details;
        }

        $response->getBody()->write((string)json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}
