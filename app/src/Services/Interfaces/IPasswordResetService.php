<?php

namespace App\Services\Interfaces;

interface IPasswordResetService
{
    public function requestPasswordReset(string $email);

    public function resetPassword(string $token, string $newPassword, string $confirmPassword);

    public function validatePassword(string $password);
}