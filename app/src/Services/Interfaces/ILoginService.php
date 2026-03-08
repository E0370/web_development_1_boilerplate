<?php 
namespace App\Services\Interfaces;
use App\Models\User;

interface ILoginService{
    public function login($username, $password);
    public function createAccount(User $user, $confirmPassword);
    public function validatePassword($password);
}
?>