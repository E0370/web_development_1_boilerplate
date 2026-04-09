<?php
namespace App\Repositories;
use App\Repositories\Interfaces\ILoginRepository;
use App\Models\User;
use App\Framework\Repository;
use Exception;

class LoginRepository extends Repository implements ILoginRepository
{
    public function login($username, $password)
    {
        try {
            $sql = 'SELECT * FROM users WHERE username = :username';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetchObject(User::class);
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function createAccount(User $user)
    {
        try {
            $sql = 'INSERT INTO users (firstname, lastname, username, password, email) VALUES (:firstname, :lastname, :username, :password, :email)';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':firstname', $user->firstname);
            $stmt->bindParam(':lastname', $user->lastname);
            $stmt->bindParam(':username', $user->username);
            $stmt->bindParam(':password', $user->password);
            $stmt->bindParam(':email', $user->email);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error creating account. " . $e->getMessage();
        }
    }

    public function usernameExists(User $user)
    {
        try {
            $sql = 'SELECT COUNT(*) FROM users WHERE username = :username';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':username', $user->username);
            $stmt->execute();
            $count = (int) $stmt->fetchColumn();
            return $count > 0;
        } catch (Exception $e) {
            echo "Error checking. " . $e->getMessage();
        }
    }

    public function emailExists(User $user)
    {
        try {
            $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':email', $user->email);
            $stmt->execute();
            $count = (int) $stmt->fetchColumn();
            return $count > 0;
        } catch (Exception $e) {
            echo "Error checking. " . $e->getMessage();
        }
    }
}
?>