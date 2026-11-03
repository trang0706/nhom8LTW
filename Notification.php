<?php

require_once __DIR__ . '/../../config/database.php';

class Notification {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(int $userId, string $title, string $message, ?string $link = null): int {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, title, message, link)
            VALUES (:user_id, :title, :message, :link)
        ");
        $stmt->execute([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'link'    => $link
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getByUser(int $userId, int $limit = 10): array {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications 
            WHERE user_id = :user_id 
            ORDER BY id DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function markAsRead(int $id, int $userId): bool {
        $stmt = $this->db->prepare("
            UPDATE notifications 
            SET is_read = 1 
            WHERE id = :id AND user_id = :user_id
        ");
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }

    public function getUnreadCount(int $userId): int {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM notifications 
            WHERE user_id = :user_id AND is_read = 0
        ");
        $stmt->execute(['user_id' => $userId]);
        return (int)$stmt->fetchColumn();
    }
}

