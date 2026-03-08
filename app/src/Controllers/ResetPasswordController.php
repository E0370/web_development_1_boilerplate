<?php

namespace App\Controllers;

use App\Services\Interfaces\IPasswordResetService;
use App\Services\PasswordResetService;
use Exception;

class ResetPasswordController
{
    private IPasswordResetService $passwordResetService;

    public function __construct()
    {
        $this->passwordResetService = new PasswordResetService();
    }

    public function showForgotPassword(): void
    {
        require __DIR__ . '/../Views/forgotpassword.php';
    }

    public function forgotPassword(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /forgot-password');
                exit();
            }

            $email = trim($_POST['email'] ?? '');
            $token = $this->passwordResetService->requestPasswordReset($email);

            $_SESSION['password_reset_message'] =
                'If an account with that email exists, a reset link has been sent.';

            if ($token) {
                $_SESSION['ResetLink'] = '/reset-password/' . urlencode($token);
            }

            header('Location: /forgot-password');
            exit();

        } catch (Exception $e) {
            $_SESSION['reset_error'] = $e->getMessage();
            header('Location: /forgot-password');
            exit();
        }
    }

    public function showResetPassword(array $vars = []): void
    {
        $token = $vars['token'] ?? '';
        require __DIR__ . '/../Views/resetpassword.php';
    }

    public function resetPassword(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /forgot-password');
                exit();
            }

            $token = trim($_POST['token'] ?? '');
            $newPassword = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            $this->passwordResetService->resetPassword($token, $newPassword, $confirmPassword);

            $_SESSION['password_reset_message'] = 'Your password has been reset successfully.';
            header('Location: /login');
            exit();

        } catch (Exception $e) {
            $_SESSION['reset_error'] = $e->getMessage();
            header('Location: /reset-password/' . urlencode($_POST['token'] ?? ''));
            exit();
        }
    }
}