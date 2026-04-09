<?php

namespace App\Services;

use App\Repositories\Interfaces\IMessageRepository;
use App\Repositories\MessageRepository;
use App\Services\Interfaces\IMessageService;
use Exception;

class MessageService implements IMessageService
{
    private IMessageRepository $messageRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
    }

    public function getConversationMessages($itemId, $currentUserId, $otherUserId)
    {
        return $this->messageRepository->getConversationMessages($itemId, $currentUserId, $otherUserId);
    }

    public function sendMessage($itemId, $senderId, $receiverId, $message)
    {
        $message = trim($message);

        if ($message === '') {
            throw new Exception("Message cannot be empty.");
        }

        if ((int) $senderId === (int) $receiverId) {
            throw new Exception("You cannot send a message to yourself.");
        }

        return $this->messageRepository->createMessage($itemId, $senderId, $receiverId, $message);
    }

    public function getUserConversations($currentUserId)
    {
        return $this->messageRepository->getUserConversations($currentUserId);
    }
}