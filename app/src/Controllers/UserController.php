<?php

namespace App\Controllers;

use App\Services\Interfaces\IMessageService;
use App\Services\MessageService;
use App\Services\Interfaces\IItemService;
use App\Services\ItemService;
use App\Repositories\UserRepository;
use Exception;

class UserController extends BaseController
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
            header('Location: /mymessages');
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
            header('Location: /mymessages');
            exit();
        }
    }

    public function getMessagesApi($vars = [])
    {
        try {
            $this->requireLogin();

            $itemId = $vars['itemId'] ?? null;
            $otherUserId = $vars['userId'] ?? null;
            $currentUserId = $_SESSION['user']['id'];

            if (!$itemId || !$otherUserId) {
                throw new Exception("Conversation not found.");
            }

            $messages = $this->messageService->getConversationMessages(
                $itemId,
                $currentUserId,
                $otherUserId
            );

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'currentUserId' => (int) $currentUserId,
                'messages' => $messages
            ]);
            exit();

        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }

    public function sendMessageApi()
    {
        try {
            $this->requireLogin();

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Invalid request.");
            }

            $itemId = $_POST['item_id'] ?? null;
            $receiverId = $_POST['receiver_id'] ?? null;
            $message = trim($_POST['message'] ?? '');
            $senderId = $_SESSION['user']['id'];

            if (!$itemId || !$receiverId || $message === '') {
                throw new Exception("Unable to send message.");
            }

            $this->messageService->sendMessage($itemId, $senderId, $receiverId, $message);

            $messages = $this->messageService->getConversationMessages(
                $itemId,
                $senderId,
                $receiverId
            );

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'currentUserId' => (int) $senderId,
                'messages' => $messages
            ]);
            exit();

        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
    }

    public function showPrivacyPolicy()
    {
        require __DIR__ . '/../Views/privacypolicy.php';
    }
}