<?php
namespace App\Models;

class Items{
    public int $id;
    public int $user_id;
    public string $title;
    public string $description;
    public string $status;
    public string $image; 
    public string $created_at;
}
?>