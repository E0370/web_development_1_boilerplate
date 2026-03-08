<?php 
namespace App\Models\Enums;

enum Role: string {
    case USER = 'user';
    case ADMIN = 'admin';
}
?>