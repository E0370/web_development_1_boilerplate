<?php

namespace App\Services;

use App\Repositories\Interfaces\IPasswordResetRepository;
use App\Repositories\PasswordResetRepository;
use App\Services\Interfaces\IPasswordResetService;
use Exception;

class PasswordResetService implements IPasswordResetService
{
    private IPasswordResetRepository $passwordResetRepository;

    public function __construct()
    {
        $this->passwordResetRepository = new PasswordResetRepository();
    }

    public function requestPasswordReset(string $email)
    {
        $email = trim($email);

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address.');
        }

        $userId = $this->passwordResetRepository->getUserIdByEmail($email);

        if (!$userId) {
            return null;
        }

        $token = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->passwordResetRepository->deleteTokensForUser($userId);
        $this->passwordResetRepository->insertResetToken($userId, $token, $expiry);

        return [
            'email' => $email,
            'token' => $token,
            'resetLink' => $this->ResetLink($token),
        ];
    }

    public function resetPassword(string $token, string $newPassword, string $confirmPassword)
    {
        $token = trim($token);

        if ($token === '') {
            throw new Exception('Invalid token.');
        }

        $this->validatePassword($newPassword);

        if ($newPassword !== $confirmPassword) {
            throw new Exception('Passwords do not match.');
        }

        $row = $this->passwordResetRepository->isTokenValid($token);

        if (!$row) {
            throw new Exception('Reset token is invalid or expired.');
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->passwordResetRepository->updateUserPassword($row['user_id'], $hashedPassword);
        $this->passwordResetRepository->deleteResetById($row['id']);
    }

    public function validatePassword(string $password)
    {
        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long.');
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception('Password must contain an uppercase letter.');
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw new Exception('Password must contain a lowercase letter.');
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception('Password must contain a number.');
        }

        if (!preg_match('/[\W]/', $password)) {
            throw new Exception('Password must contain a special character.');
        }
    }

    private function ResetLink(string $token)
    {
        $appUrl = rtrim($_ENV['APP_URL'] ?? 'http://localhost', '/');
        return $appUrl . '/reset-password/' . urlencode($token);
    }
}