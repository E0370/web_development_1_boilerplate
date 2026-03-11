<?php

namespace App\Repositories\Interfaces;

interface IMessageRepository
{
    public function getConversationMessages($itemId, $currentUserId, $otherUserId);

    public function createMessage($itemId, $senderId, $receiverId, $message);

    public function getUserConversations($currentUserId);
}