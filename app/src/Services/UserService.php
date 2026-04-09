<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\IUserService;
use Exception;

class UserService implements IUserService
{
    private IUserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function createUser(User $user, $confirmPassword, $currentUserRole)
    {
        $this->requireAdmin($currentUserRole);

        if (
            empty(trim($user->firstname ?? '')) ||
            empty(trim($user->lastname ?? '')) ||
            empty(trim($user->username ?? '')) ||
            empty(trim($user->email ?? '')) ||
            empty(trim($user->password ?? '')) ||
            empty(trim($confirmPassword ?? '')) ||
            empty(trim($user->role ?? ''))
        ) {
            throw new Exception("All fields are required.");
        }

        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address.");
        }

        if (!in_array($user->role, ['user', 'admin'])) {
            throw new Exception("Invalid role selected.");
        }

        if ($this->userRepository->usernameExists($user->username)) {
            throw new Exception("Username already exists.");
        }

        if ($this->userRepository->emailExists($user->email)) {
            throw new Exception("Email already exists.");
        }

        $this->validatePassword($user->password);

        if ($user->password !== $confirmPassword) {
            throw new Exception("Passwords do not match.");
        }

        $user->password = password_hash($user->password, PASSWORD_DEFAULT);

        return $this->userRepository->createUser($user);
    }

    public function getAllUsers($currentUserRole)
    {
        $this->requireAdmin($currentUserRole);
        return $this->userRepository->getAllUsers();
    }

    public function deleteUser($userId, $currentUserRole)
    {
        $this->requireAdmin($currentUserRole);
        return $this->userRepository->deleteUser($userId);
    }

    public function updateUserRole($userId, $role, $currentUserRole, $currentUserId)
    {
        $this->requireAdmin($currentUserRole);

        if (!in_array($role, ['user', 'admin'])) {
            throw new Exception("Invalid role.");
        }

        if (empty($userId)) {
            throw new Exception("Invalid user.");
        }

        // admin cannot change his own role
        if ((string)$userId === (string)$currentUserId) {
            throw new Exception("You cannot change your own role.");
        }

        return $this->userRepository->updateUserRole($userId, $role);
    }

    private function requireAdmin($currentUserRole)
    {
        if ($currentUserRole !== 'admin') {
            throw new Exception("Access denied.");
        }
    }

    private function validatePassword($password)
    {
        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long.");
        }
        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception("Password must contain an uppercase letter.");
        }
        if (!preg_match('/[a-z]/', $password)) {
            throw new Exception("Password must contain a lowercase letter.");
        }
        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception("Password must contain a number.");
        }
        if (!preg_match('/[\W]/', $password)) {
            throw new Exception("Password must contain a special character.");
        }
    }
}