<?php

namespace App\Services\Interfaces;

interface IMailService
{
    public function sendPasswordResetEmail(string $toEmail, string $resetLink);
}