<?php

namespace App\Services;

use App\Models\Items;
use App\Repositories\Interfaces\IItemRepository;
use App\Repositories\ItemRepository;
use App\Services\Interfaces\IItemService;
use Exception;

class ItemService implements IItemService
{
    private IItemRepository $itemRepository;

    public function __construct()
    {
        $this->itemRepository = new ItemRepository();
    }

    public function getAllItems()
    {
        return $this->itemRepository->getAllItems();
    }

    public function getItemById($id)
    {
        return $this->itemRepository->getItemById($id);
    }

    public function getItemsByUserId($userId)
    {
        return $this->itemRepository->getItemsByUserId($userId);
    }

    public function createItem(Items $item, $imageFile)
    {
        $this->validateItemData($item);
        $item->image = $this->handleImageUpload($imageFile);

        return $this->itemRepository->createItem($item);
    }

    public function updateItem(Items $item, $imageFile, $currentUserId, $currentUserRole)
    {
        $existingItem = $this->itemRepository->getItemById($item->id);

        if (!$existingItem) {
            throw new Exception("Item not found.");
        }

        $this->authorizeItemAction($existingItem, $currentUserId, $currentUserRole);
        $this->validateItemData($item);

        if (isset($imageFile) && isset($imageFile['error']) && $imageFile['error'] === 0) {
            $item->image = $this->handleImageUpload($imageFile);
        } else {
            $item->image = $existingItem->image;
        }

        return $this->itemRepository->updateItem($item);
    }

    public function deleteItem($itemId, $currentUserId, $currentUserRole)
    {
        $existingItem = $this->itemRepository->getItemById($itemId);

        if (!$existingItem) {
            throw new Exception("Item not found.");
        }

        $this->authorizeItemAction($existingItem, $currentUserId, $currentUserRole);

        return $this->itemRepository->deleteItem($itemId);
    }

    private function validateItemData(Items $item)
    {
        if (
            empty(trim($item->title ?? '')) ||
            empty(trim($item->description ?? '')) ||
            empty(trim($item->status ?? ''))
        ) {
            throw new Exception("All fields are required.");
        }

        if (!in_array($item->status, ['lost', 'found'])) {
            throw new Exception("Invalid status selected.");
        }
    }

    private function authorizeItemAction(Items $item, $currentUserId, $currentUserRole)
    {
        $isOwner = ((int)$item->user_id === (int)$currentUserId);
        $isAdmin = ($currentUserRole === 'admin');

        if (!$isOwner && !$isAdmin) {
            throw new Exception("You are not allowed to perform this action.");
        }
    }

    private function handleImageUpload($imageFile)
    {
        if (!isset($imageFile) || !isset($imageFile['error']) || $imageFile['error'] !== 0) {
            throw new Exception("Image is required.");
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($imageFile['type'], $allowedTypes)) {
            throw new Exception("Only JPG, PNG or WEBP images are allowed.");
        }

        if ($imageFile['size'] > 2 * 1024 * 1024) {
            throw new Exception("Image size must be under 2MB.");
        }

        $extension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
        $fileName = uniqid('item_', true) . '.' . $extension;

        $uploadDirectory = __DIR__ . '/../../public/assets/images/';
        $uploadPath = $uploadDirectory . $fileName;

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        if (!move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
            throw new Exception("Failed to upload image.");
        }

        return $fileName;
    }
}