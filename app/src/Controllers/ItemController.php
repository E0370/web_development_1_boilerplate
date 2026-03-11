<?php

namespace App\Controllers;

use App\Models\Items;
use App\Services\ItemService;
use App\Services\Interfaces\IItemService;
use Exception;

class ItemController
{
    private IItemService $itemService;

    public function __construct()
    {
        $this->itemService = new ItemService();
    }

    public function showAllItems()
    {
        $items = $this->itemService->getAllItems();
        require __DIR__ . '/../Views/homepage.php';
    }

    public function showCreateItem()
    {
        $this->requireLogin();
        require __DIR__ . '/../Views/createitem.php';
    }

    public function createItem()
    {
        try {
            $this->requireLogin();

            $item = new Items();
            $item->user_id = $_SESSION['user']['id'];
            $item->title = trim($_POST['title'] ?? '');
            $item->description = trim($_POST['description'] ?? '');
            $item->status = trim($_POST['status'] ?? '');

            $this->itemService->createItem($item, $_FILES['image'] ?? null);

            $_SESSION['success_message'] = 'Item created successfully.';
            header("Location: /myitems");
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: /createitem");
            exit();
        }
    }

    public function showMyItems()
    {
        $this->requireLogin();

        $userId = $_SESSION['user']['id'];
        $items = $this->itemService->getItemsByUserId($userId);

        require __DIR__ . '/../Views/myitems.php';
    }

    public function showEditItem($vars = [])
    {
        try {
            $this->requireLogin();

            $itemId = $vars['id'] ?? null;
            if (!$itemId) {
                throw new Exception('Item not found.');
            }

            $item = $this->itemService->getItemById($itemId);

            if (!$item) {
                throw new Exception('Item not found.');
            }

            $this->authorizeViewOrEdit($item);

            require __DIR__ . '/../Views/edititem.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: /myitems");
            exit();
        }
    }

    public function updateItem($vars = [])
    {
        try {
            $this->requireLogin();

            $itemId = $vars['id'] ?? null;
            if (!$itemId) {
                throw new Exception('Item not found.');
            }

            $item = new Items();
            $item->id = (int)$itemId;
            $item->title = trim($_POST['title'] ?? '');
            $item->description = trim($_POST['description'] ?? '');
            $item->status = trim($_POST['status'] ?? '');

            $this->itemService->updateItem(
                $item,
                $_FILES['image'] ?? null,
                $_SESSION['user']['id'],
                $_SESSION['user']['role']
            );

            $_SESSION['success_message'] = "Item updated successfully.";
            header("Location: /myitems");
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: /edititem/" . urlencode($vars['id'] ?? ''));
            exit();
        }
    }

    public function deleteItem($vars = [])
    {
        try {
            $this->requireLogin();

            $itemId = $vars['id'] ?? null;
            if (!$itemId) {
                throw new Exception('Item not found.');
            }

            $this->itemService->deleteItem(
                $itemId,
                $_SESSION['user']['id'],
                $_SESSION['user']['role']
            );

            $_SESSION['success_message'] = "Item deleted successfully.";
            header("Location: /myitems");
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: /myitems");
            exit();
        }
    }

    private function requireLogin()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit();
        }
    }

    private function authorizeViewOrEdit($item)
    {
        $currentUserId = $_SESSION['user']['id'];
        $currentUserRole = $_SESSION['user']['role'];

        $isOwner = ((int)$item->user_id === (int)$currentUserId);
        $isAdmin = ($currentUserRole === 'admin');

        if (!$isOwner && !$isAdmin) {
            throw new Exception('You are not allowed to access this item.');
        }
    }
}