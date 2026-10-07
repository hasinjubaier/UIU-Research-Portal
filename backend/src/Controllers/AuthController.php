<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Utils\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService($this->db);
    }

    public function register(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->getBody($request);

        Validator::make($body, [
            'name'     => 'required|min:2',
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ])->validate();

        $result = $this->authService->register($body);
        return $this->json($response, $result, 201, 'Registration successful. Verification email dispatched.');
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->getBody($request);

        Validator::make($body, [
            'email'    => 'required|email',
            'password' => 'required',
        ])->validate();

        $result = $this->authService->login($body['email'], $body['password']);
        return $this->json($response, $result, 200, 'Login successful');
    }

    public function forgotPassword(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->getBody($request);

        Validator::make($body, [
            'email' => 'required|email',
        ])->validate();

        $this->authService->forgotPassword($body['email']);
        return $this->json($response, ['sent' => true], 200, 'If that email exists, a password reset link has been sent.');
    }

    public function resetPassword(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $this->getBody($request);

        Validator::make($body, [
            'token'    => 'required',
            'password' => 'required|min:6',
        ])->validate();

        $this->authService->resetPassword($body['token'], $body['password']);
        return $this->json($response, ['reset' => true], 200, 'Password has been reset successfully.');
    }

    public function verifyEmail(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = $this->getQueryParams($request);
        $token = $params['token'] ?? '';

        if (empty($token)) {
            return $this->error($response, 'Verification token required', 400);
        }

        $this->authService->verifyEmail($token);
        return $this->json($response, ['verified' => true], 200, 'Email address verified successfully.');
    }

    public function me(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getUserId($request);
        $user = $this->authService->getCurrentUser($userId);

        if (!$user) {
            return $this->error($response, 'User not found', 404);
        }

        return $this->json($response, $user, 200);
    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->json($response, ['logged_out' => true], 200, 'Logged out successfully');
    }
}
