<?php

namespace App\Services;

use App\Exceptions\AuthenticationException;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Utils\EmailService;
use App\Utils\JWT;
use App\Utils\Logger;
use PDO;

class AuthService
{
    private PDO $db;
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    public function register(array $data): array
    {
        $existing = $this->userModel->findByEmail($data['email']);
        if ($existing) {
            throw new ValidationException('Validation failed', [
                'email' => ['A user with this email address already exists.']
            ]);
        }

        // Generate initials
        $nameParts = explode(' ', trim($data['name']));
        $initials = '';
        foreach ($nameParts as $p) {
            if (!empty($p)) {
                $initials .= strtoupper($p[0]);
            }
        }
        $initials = substr($initials, 0, 4);

        $userId = $this->userModel->create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'department'    => $data['department'] ?? 'CSE',
            'academic_year' => $data['academic_year'] ?? '1st Year',
            'role'          => $data['role'] ?? 'Student',
            'initials'      => $initials,
            'bio'           => $data['bio'] ?? null,
            'status'        => 'online',
            'reputation'    => 100, // Welcome points
        ]);

        if (!empty($data['skills']) && is_array($data['skills'])) {
            $this->userModel->syncSkills((int)$userId, $data['skills']);
        }

        // Send verification email
        $verifyToken = JWT::encode(['sub' => (int)$userId, 'type' => 'email_verify'], 86400);
        EmailService::sendVerificationEmail($data['email'], $verifyToken);

        Logger::info("User registered successfully: ID {$userId}, Email {$data['email']}");

        $user = $this->userModel->getFullProfile((int)$userId);
        $token = JWT::encode([
            'sub'   => (int)$userId,
            'email' => $data['email'],
            'role'  => $user['role'] ?? 'Student',
        ]);

        return [
            'token' => $token,
            'user'  => $user,
        ];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            throw new AuthenticationException('Invalid email or password', 401);
        }

        if (!password_verify($password, $user['password_hash'])) {
            throw new AuthenticationException('Invalid email or password', 401);
        }

        // Update status to online
        $this->userModel->update((int)$user['id'], ['status' => 'online']);

        $fullUser = $this->userModel->getFullProfile((int)$user['id']);
        $token = JWT::encode([
            'sub'   => (int)$user['id'],
            'email' => $user['email'],
            'role'  => $user['role'] ?? 'Student',
        ]);

        Logger::info("User logged in successfully: ID {$user['id']}");

        return [
            'token' => $token,
            'user'  => $fullUser,
        ];
    }

    public function forgotPassword(string $email): bool
    {
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            // For security, still return true so attacker cannot enumerate emails
            return true;
        }

        $resetToken = JWT::encode(['sub' => (int)$user['id'], 'type' => 'pwd_reset'], 3600);
        return EmailService::sendPasswordResetEmail($email, $resetToken);
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $decoded = JWT::decode($token);
        if (!$decoded || ($decoded['type'] ?? '') !== 'pwd_reset') {
            throw new ValidationException('Invalid or expired password reset token');
        }

        $userId = (int)$decoded['sub'];
        $this->userModel->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        Logger::info("Password reset successfully for User {$userId}");
        return true;
    }

    public function verifyEmail(string $token): bool
    {
        $decoded = JWT::decode($token);
        if (!$decoded || ($decoded['type'] ?? '') !== 'email_verify') {
            throw new ValidationException('Invalid or expired verification token');
        }

        $userId = (int)$decoded['sub'];
        $this->userModel->update($userId, [
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);

        Logger::info("Email verified successfully for User {$userId}");
        return true;
    }

    public function getCurrentUser(int $userId): ?array
    {
        return $this->userModel->getFullProfile($userId);
    }
}
