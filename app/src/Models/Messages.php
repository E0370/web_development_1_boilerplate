<?php
 namespace App\Models;

 class Messages{
    public int $id;
    public int $sender_id;
    public int $receiver_id;
    public int $item_id;
    public string $message;
    public string $created_at;
 }
?>