<?php
namespace App\Repositories;
use App\Framework\Repository;
use App\Repositories\Interfaces\IPasswordResetRepository;
use Exception;
use PDO;

class PasswordResetRepository extends Repository implements IPasswordResetRepository
{
    public function getUserIdByEmail($email)
    {
        try {
            $sql = 'SELECT id FROM users WHERE email = :email LIMIT 1';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $id = $stmt->fetchColumn();
            return $id ? (int) $id : null;
        } catch (Exception $e) {
            echo "Error fetching user ID by email: " . $e->getMessage();
        }
    }

    public function deleteTokensForUser($userId)
    {
        try {
            $sql = 'DELETE FROM password_resets WHERE user_id = :user_id';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error deleting tokens for user: " . $e->getMessage();
        }
    }

    public function insertResetToken($userId, $token, $expiry)
    {
        try {
            $sql = 'INSERT INTO password_resets (user_id, token, token_expiry) VALUES (:user_id, :token, :token_expiry)';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':token_expiry', $expiry);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error inserting reset token: " . $e->getMessage();
        }
    }

    public function isTokenValid($token)
    {
        try {
            $sql = 'SELECT id, user_id, token_expiry FROM password_resets WHERE token = :token AND token_expiry > NOW() LIMIT 1';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;

        } catch (Exception $e) {
            echo "Error token is not valid: " . $e->getMessage();
            return null;
        }
    }

    public function updateUserPassword($userId, $hashedPassword)
    {
        try {
            $sql = 'UPDATE users SET password = :password WHERE id = :user_id';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error updating user password: " . $e->getMessage();
        }
    }

    public function deleteResetById($resetId)
    {
        try {
            $sql = 'DELETE FROM password_resets WHERE id = :id';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $resetId);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Error deleting reset by ID: " . $e->getMessage();
        }
    }

}
?>