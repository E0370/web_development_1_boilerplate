<?php

namespace App\Repositories;

use App\Models\Items;
use App\Framework\Repository;
use App\Repositories\Interfaces\IItemRepository;
use Exception;
use PDO;

class ItemRepository extends Repository implements IItemRepository
{
    public function getAllItems()
    {
        try {
            $sql = 'SELECT * FROM items ORDER BY created_at DESC';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS, Items::class);
        } catch (Exception $e) {
            throw new Exception('Error fetching items: ' . $e->getMessage());
        }
    }

    public function getItemById($id)
    {
        try {
            $sql = 'SELECT * FROM items WHERE id = :id LIMIT 1';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, Items::class);
            $item = $stmt->fetch();

            return $item ?: null;
        } catch (Exception $e) {
            throw new Exception('Error fetching item: ' . $e->getMessage());
        }
    }

    public function getItemsByUserId($userId)
    {
        try {
            $sql = 'SELECT * FROM items WHERE user_id = :user_id ORDER BY created_at DESC';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS, Items::class);
        } catch (Exception $e) {
            throw new Exception('Error fetching items by user: ' . $e->getMessage());
        }
    }

    public function createItem(Items $item)
    {
        try {
            $sql = 'INSERT INTO items (user_id, title, description, status, image, created_at)
                    VALUES (:user_id, :title, :description, :status, :image, NOW())';

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':user_id', $item->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $item->title);
            $stmt->bindParam(':description', $item->description);
            $stmt->bindParam(':status', $item->status);
            $stmt->bindParam(':image', $item->image);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception('Error creating item: ' . $e->getMessage());
        }
    }

    public function updateItem(Items $item)
    {
        try {
            $sql = 'UPDATE items
                    SET title = :title,
                        description = :description,
                        status = :status,
                        image = :image
                    WHERE id = :id';

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':title', $item->title);
            $stmt->bindParam(':description', $item->description);
            $stmt->bindParam(':status', $item->status);
            $stmt->bindParam(':image', $item->image);
            $stmt->bindParam(':id', $item->id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception('Error updating item: ' . $e->getMessage());
        }
    }

    public function deleteItem($itemId)
    {
        try {
            $sql = 'DELETE FROM items WHERE id = :id';
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception('Error deleting item: ' . $e->getMessage());
        }
    }
}