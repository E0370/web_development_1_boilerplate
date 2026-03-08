<?php
namespace App\Services\Interfaces;

use App\Models\Items;
interface IItemService
{
    public function getAllItems();
    public function getItemById($id);
    public function getItemsByUserId($userId);
    public function createItem(Items $item, $imageFile);
    public function updateItem(Items $item, $imageFile, $currentUserId, $currentUserRole);
    public function deleteItem($itemId, $currentUserId, $currentUserRole);
}