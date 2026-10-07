<?php

namespace App\Utils;

class EmailService
{
    /**
     * Send email notification (logs in development, uses mail() or mock)
     */
    public static function send(string $to, string $subject, string $htmlBody, string $altBody = ''): bool
    {
        $settings = require __DIR__ . '/../../config/settings.php';
        $appName = $settings['app']['name'] ?? 'UIU Research Portal';

        Logger::info("Sending email to {$to}: Subject: '{$subject}'");

        // In development environment, log the email content
        if (($settings['app']['env'] ?? 'development') === 'development') {
            Logger::debug("Email sent to {$to}: [{$subject}]\n" . strip_tags($htmlBody));
            return true;
        }

        // Production mail sending
        $from = $settings['mail']['from_address'] ?? 'noreply@uiu.ac.bd';
        $headers = "From: {$from}\r\n" .
                   "Reply-To: {$from}\r\n" .
                   "X-Mailer: PHP/" . phpversion() . "\r\n" .
                   "Content-Type: text/html; charset=UTF-8\r\n";

        return @mail($to, $subject, $htmlBody, $headers);
    }

    /**
     * Send email verification link
     */
    public static function sendVerificationEmail(string $email, string $token): bool
    {
        $settings = require __DIR__ . '/../../config/settings.php';
        $baseUrl = $settings['app']['url'] ?? 'http://localhost:8000';
        $verifyUrl = "{$baseUrl}/api/auth/verify-email?token={$token}";

        $subject = "Verify your UIU Research Portal account";
        $html = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;'>
                <h2 style='color: #2563eb;'>Welcome to UIU Research Portal</h2>
                <p>Thank you for creating an academic collaboration account. Please verify your email address to activate all features.</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$verifyUrl}' style='background: #2563eb; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;'>Verify Email Address</a>
                </div>
                <p style='color: #64748b; font-size: 13px;'>If you did not register for this account, please ignore this email.</p>
            </div>
        ";

        return self::send($email, $subject, $html);
    }

    /**
     * Send password reset token email
     */
    public static function sendPasswordResetEmail(string $email, string $token): bool
    {
        $settings = require __DIR__ . '/../../config/settings.php';
        $baseUrl = $settings['app']['url'] ?? 'http://localhost:8000';
        $resetUrl = "{$baseUrl}/auth/reset-password.html?token={$token}";

        $subject = "Reset your UIU Research Portal password";
        $html = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;'>
                <h2 style='color: #dc2626;'>Password Reset Request</h2>
                <p>We received a request to reset your password. Click the link below to set a new password:</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$resetUrl}' style='background: #dc2626; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;'>Reset Password</a>
                </div>
                <p style='color: #64748b; font-size: 13px;'>This password reset link will expire in 1 hour.</p>
            </div>
        ";

        return self::send($email, $subject, $html);
    }

    /**
     * Send academic notification email
     */
    public static function sendNotificationEmail(string $email, string $message, string $title = 'Portal Notification'): bool
    {
        $subject = "[UIU Research Portal] {$title}";
        $html = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;'>
                <h3 style='color: #0f172a;'>{$title}</h3>
                <p style='color: #334155; line-height: 1.6;'>{$message}</p>
                <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;' />
                <p style='color: #94a3b8; font-size: 12px;'>UIU Research Collaboration Platform — United International University</p>
            </div>
        ";

        return self::send($email, $subject, $html);
    }
}
