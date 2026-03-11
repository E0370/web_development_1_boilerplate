<?php
namespace App\Repositories\Interfaces;
use App\Models\User;

interface IUserRepository
{
    public function createUser(User $user);
    public function getAllUsers();
    public function getUserById($id);
    public function deleteUser($userId);
    public function updateUserRole($userId, $role);
    public function usernameExists($username);
    public function emailExists($email);
}