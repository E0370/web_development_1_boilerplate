<?php
namespace App\Repositories\Interfaces;
use App\Models\Items;
interface IItemRepository
{
    public function getAllItems();
    public function getItemById($id);
    public function getItemsByUserId($userId);
    public function createItem(Items $item);
    public function updateItem(Items $item);
    public function deleteItem($itemId);
}