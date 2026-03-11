<?php

namespace App\Controllers;

use App\Services\Interfaces\IMessageService;
use App\Services\MessageService;
use App\Services\Interfaces\IItemService;
use App\Services\ItemService;
use App\Repositories\UserRepository;
use Exception;

class UserController
{
    private IMessageService $messageService;
    private IItemService $itemService;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->messageService = new MessageService();
        $this->itemService = new ItemService();
        $this->userRepository = new UserRepository();
    }

    public function showDashboard()
    {
        $this->requireLogin();
        require __DIR__ . '/../Views/userdashboard.php';
    }

    public function showInbox()
    {
        try {
            $this->requireLogin();

            $currentUserId = $_SESSION['user']['id'];
            $conversations = $this->messageService->getUserConversations($currentUserId);

            require __DIR__ . '/../Views/inbox.php';
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /');
            exit();
        }
    }

    public function showMessages($vars = [])
    {
        try {
            $this->requireLogin();

            $itemId = $vars['itemId'] ?? null;
            $otherUserId = $vars['userId'] ?? null;

            if (!$itemId || !$otherUserId) {
                throw new Exception("Conversation not found.");
            }

            $item = $this->itemService->getItemById($itemId);

            if (!$item) {
                throw new Exception("Item not found.");
            }

            $otherUser = $this->userRepository->getUserById($otherUserId);

            if (!$otherUser) {
                throw new Exception("User not found.");
            }

            $currentUserId = $_SESSION['user']['id'];

            $messages = $this->messageService->getConversationMessages(
                $itemId,
                $currentUserId,
                $otherUserId
            );

            $receiverId = $otherUserId;
            $otherUsername = $otherUser->username;

            require __DIR__ . '/../Views/messages.php';

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /my-messages');
            exit();
        }
    }

    public function sendMessage()
    {
        try {
            $this->requireLogin();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /');
                exit();
            }

            $itemId = $_POST['item_id'] ?? null;
            $receiverId = $_POST['receiver_id'] ?? null;
            $message = $_POST['message'] ?? '';
            $senderId = $_SESSION['user']['id'];

            if (!$itemId || !$receiverId) {
                throw new Exception("Unable to send message.");
            }

            $this->messageService->sendMessage($itemId, $senderId, $receiverId, $message);

            header('Location: /messages/' . $itemId . '/' . $receiverId);
            exit();

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /my-messages');
            exit();
        }
    }

    private function requireLogin()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }
}