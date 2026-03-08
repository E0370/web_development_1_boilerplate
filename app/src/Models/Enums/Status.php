<?php
namespace App\Models\Enums;

enum Status: string {
    case Lost = 'lost';
    case Found = 'found';
}
?>