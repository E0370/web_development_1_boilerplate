<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface IUserService
{
    public function createUser(User $user, $confirmPassword, $currentUserRole);

    public function getAllUsers($currentUserRole);

    public function deleteUser($userId, $currentUserRole);

    public function updateUserRole($userId, $role, $currentUserRole, $currentUserId);
}