<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\Interfaces\IUserService;
use App\Services\UserService;
use App\Services\Interfaces\IItemService;
use App\Services\ItemService;
use Exception;

class AdminController extends BaseController
{
    private IUserService $userService;
    private IItemService $itemService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->itemService = new ItemService();
    }

    public function showDashboard()
    {
        $this->requireAdmin();

        $users = $this->userService->getAllUsers($_SESSION['user']['role']);
        $items = $this->itemService->getAllItems();

        require __DIR__ . '/../Views/admindashboard.php';
    }

    public function showCreateUser()
    {
        $this->requireAdmin();
        $formAction = '/admin/createaccount';
        require __DIR__ . '/../Views/createaccount.php';
    }

    public function createUser()
    {
        try {
            $this->requireAdmin();

            $user = new User();
            $user->firstname = trim($_POST['firstname'] ?? '');
            $user->lastname = trim($_POST['lastname'] ?? '');
            $user->username = trim($_POST['username'] ?? '');
            $user->email = trim($_POST['email'] ?? '');
            $user->password = $_POST['password'] ?? '';
            $user->role = 'user';

            $confirmPassword = $_POST['confirmPassword'] ?? '';

            $this->userService->createUser($user, $confirmPassword, $_SESSION['user']['role']);

            $_SESSION['success_message'] = 'User created successfully.';
            header('Location: /admin');
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /admin/createaccount');
            exit();
        }
    }

    public function showEditItem($vars = [])
    {
        try {
            $this->requireAdmin();

            $itemId = $vars['id'] ?? null;
            if (!$itemId) {
                throw new Exception('Item not found.');
            }

            $item = $this->itemService->getItemById($itemId);

            if (!$item) {
                throw new Exception('Item not found.');
            }

            require __DIR__ . '/../Views/edititem.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /admin');
            exit();
        }
    }

    public function updateItem($vars = [])
    {
        try {
            $this->requireAdmin();

            $itemId = $vars['id'] ?? null;
            if (!$itemId) {
                throw new Exception('Item not found.');
            }

            $item = new \App\Models\Items();
            $item->id = (int) $itemId;
            $item->title = trim($_POST['title'] ?? '');
            $item->description = trim($_POST['description'] ?? '');
            $item->status = trim($_POST['status'] ?? '');

            $this->itemService->updateItem(
                $item,
                $_FILES['image'] ?? null,
                $_SESSION['user']['id'],
                $_SESSION['user']['role']
            );

            $_SESSION['success_message'] = 'Item updated successfully.';
            header('Location: /admin');
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /admin/edititem/' . urlencode($vars['id'] ?? ''));
            exit();
        }
    }

    public function deleteItem($vars = [])
    {
        try {
            $this->requireAdmin();

            $itemId = $vars['id'] ?? null;
            if (!$itemId) {
                throw new Exception('Item not found.');
            }

            $this->itemService->deleteItem(
                $itemId,
                $_SESSION['user']['id'],
                $_SESSION['user']['role']
            );

            $_SESSION['success_message'] = 'Post deleted successfully.';
            header('Location: /admin');
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /admin');
            exit();
        }
    }

    public function updateUserRole($vars = [])
    {
        try {
            $this->requireAdmin();

            $userId = $vars['id'] ?? null;
            $role = $_POST['role'] ?? '';

            $this->userService->updateUserRole(
                $userId,
                $role,
                $_SESSION['user']['role'],
                $_SESSION['user']['id']
            );

            $_SESSION['success_message'] = 'User role updated successfully.';
            header('Location: /admin');
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /admin');
            exit();
        }
    }

    public function deleteUser($vars = [])
    {
        try {
            $this->requireAdmin();

            $userId = $vars['id'] ?? null;
            $this->userService->deleteUser($userId, $_SESSION['user']['role']);

            $_SESSION['success_message'] = 'User deleted successfully.';
            header('Location: /admin');
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /admin');
            exit();
        }
    }
}