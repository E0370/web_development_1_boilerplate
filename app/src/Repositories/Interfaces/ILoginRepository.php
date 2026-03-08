<?php 
namespace App\Repositories\Interfaces;
use App\Models\User;

interface ILoginRepository{
    public function login($username, $password);
    public function createAccount(User $user);
    public function usernameExists(User $user);
    public function emailExists(User $user);
}
?>