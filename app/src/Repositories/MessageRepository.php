<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\Interfaces\IMessageRepository;
use Exception;
use PDO;

class MessageRepository extends Repository implements IMessageRepository
{
    public function getConversationMessages($itemId, $currentUserId, $otherUserId)
    {
        try {
            $sql = "SELECT *
                    FROM messages
                    WHERE item_id = :item_id
                    AND (
                        (sender_id = :current_user_id AND receiver_id = :other_user_id)
                        OR
                        (sender_id = :other_user_id AND receiver_id = :current_user_id)
                    )
                    ORDER BY created_at ASC";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->bindParam(':current_user_id', $currentUserId, PDO::PARAM_INT);
            $stmt->bindParam(':other_user_id', $otherUserId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching conversation messages: " . $e->getMessage());
        }
    }

    public function createMessage($itemId, $senderId, $receiverId, $message)
    {
        try {
            $sql = "INSERT INTO messages (sender_id, receiver_id, item_id, message)
                    VALUES (:sender_id, :receiver_id, :item_id, :message)";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->bindParam(':receiver_id', $receiverId, PDO::PARAM_INT);
            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->bindParam(':message', $message);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error sending message: " . $e->getMessage());
        }
    }

    public function getUserConversations($currentUserId)
    {
        try {
            $sql = "SELECT 
                        m.item_id,
                        i.title AS item_title,
                        i.image AS item_image,
                        CASE
                            WHEN m.sender_id = :current_user_id THEN m.receiver_id
                            ELSE m.sender_id
                        END AS other_user_id,
                        CASE
                            WHEN m.sender_id = :current_user_id THEN u2.username
                            ELSE u1.username
                        END AS other_username,
                        m.message,
                        m.created_at
                    FROM messages m
                    INNER JOIN items i ON i.id = m.item_id
                    INNER JOIN users u1 ON u1.id = m.sender_id
                    INNER JOIN users u2 ON u2.id = m.receiver_id
                    WHERE m.sender_id = :current_user_id OR m.receiver_id = :current_user_id
                    ORDER BY m.created_at DESC";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':current_user_id', $currentUserId, PDO::PARAM_INT);
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $unique = [];
            foreach ($rows as $row) {
                $key = $row['item_id'] . '_' . $row['other_user_id'];
                if (!isset($unique[$key])) {
                    $unique[$key] = $row;
                }
            }

            return array_values($unique);
        } catch (Exception $e) {
            throw new Exception("Error fetching conversations: " . $e->getMessage());
        }
    }
}