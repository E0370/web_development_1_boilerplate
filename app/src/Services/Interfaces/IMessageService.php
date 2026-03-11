<?php

namespace App\Services\Interfaces;

interface IMessageService
{
    public function getConversationMessages($itemId, $currentUserId, $otherUserId);

    public function sendMessage($itemId, $senderId, $receiverId, $message);

    public function getUserConversations($currentUserId);
}