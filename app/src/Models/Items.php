<?php
namespace App\Models;

class Items{
    public int $id;
    public int $user_id;
    public string $title;
    public string $description;
    public string $status;
    public string $image; // image path
    public string $created_at;
}
?>