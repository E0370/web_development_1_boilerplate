<?php 
namespace App\Models;

class PasswordReset{
    public int $id;
    public int $user_id;
    public string $token;
    public string $token_expiry;
}
?>