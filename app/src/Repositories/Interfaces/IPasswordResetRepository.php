<?php 
namespace App\Repositories\Interfaces;

interface IPasswordResetRepository{
    public function getUserIdByEmail($email);
    public function deleteTokensForUser($userId);
    public function insertResetToken($userId, $token, $expiry);
    public function isTokenValid($token);
    public function updateUserPassword($userId, $hashedPassword);
    public function deleteResetById($resetId);
}

?>