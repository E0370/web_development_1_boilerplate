<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use Exception;
use PDO;

class UserRepository extends Repository implements IUserRepository
{
    public function createUser(User $user)
    {
        try {
            $sql = "INSERT INTO users (firstname, lastname, username, email, password, role)
                    VALUES (:firstname, :lastname, :username, :email, :password, :role)";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':firstname', $user->firstname);
            $stmt->bindParam(':lastname', $user->lastname);
            $stmt->bindParam(':username', $user->username);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':password', $user->password);
            $stmt->bindParam(':role', $user->role);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error creating user: " . $e->getMessage());
        }
    }

    public function getAllUsers()
    {
        try {
            $sql = "SELECT * FROM users ORDER BY id DESC";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
        } catch (Exception $e) {
            throw new Exception("Error fetching users: " . $e->getMessage());
        }
    }

    public function getUserById($id)
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
            $user = $stmt->fetch();

            return $user ?: null;
        } catch (Exception $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error deleting user: " . $e->getMessage());
        }
    }

    public function updateUserRole($userId, $role)
    {
        try {
            $sql = "UPDATE users SET role = :role WHERE id = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error updating user role: " . $e->getMessage());
        }
    }

    public function usernameExists($username)
    {
        try {
            $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            return (int)$stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            throw new Exception("Error checking username: " . $e->getMessage());
        }
    }

    public function emailExists($email)
    {
        try {
            $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            return (int)$stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            throw new Exception("Error checking email: " . $e->getMessage());
        }
    }
}