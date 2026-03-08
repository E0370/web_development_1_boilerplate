<?php 
namespace App\Services\Interfaces;

interface IPasswordResetService{
    public function requestPasswordReset($email);
    public function resetPassword($token, $newPassword, $confirmPassword);
    public function validatePassword($password);
}
?>