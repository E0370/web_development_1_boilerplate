<?php

namespace App\Controllers;

use App\Services\Interfaces\IMailService;
use App\Services\Interfaces\IPasswordResetService;
use App\Services\MailService;
use App\Services\PasswordResetService;
use Exception;

class ResetPasswordController
{
    private IPasswordResetService $passwordResetService;
    private IMailService $mailService;

    public function __construct()
    {
        $this->passwordResetService = new PasswordResetService();
        $this->mailService = new MailService();
    }

    public function showForgotPassword()
    {
        require __DIR__ . '/../Views/forgotpassword.php';
    }

    public function forgotPassword()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /forgotpassword');
                exit();
            }

            $email = trim($_POST['email'] ?? '');
            $resetData = $this->passwordResetService->requestPasswordReset($email);

            if ($resetData !== null) {
                $this->mailService->sendPasswordResetEmail(
                    $resetData['email'],
                    $resetData['resetLink']
                );
            }

            $_SESSION['password_reset_message'] =
                'If an account with that email exists, a reset link has been sent.';

            header('Location: /forgot-password');
            exit();
        } catch (Exception $e) {
            $_SESSION['reset_error'] = $e->getMessage();
            header('Location: /forgotpassword');
            exit();
        }
    }

    public function showResetPassword(array $vars = [])
    {
        $token = $vars['token'] ?? '';
        require __DIR__ . '/../Views/resetpassword.php';
    }

    public function resetPassword()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /forgotpassword');
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
            header('Location: /resetpassword/' . urlencode($_POST['token'] ?? ''));
            exit();
        }
    }
}