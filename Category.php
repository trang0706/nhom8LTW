<?php

require_once __DIR__ . '/../../config/database.php';

class Category {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT c.*, COUNT(j.id) as job_count
            FROM categories c
            LEFT JOIN jobs j ON c.id = j.category_id AND j.status = 'open'
            GROUP BY c.id
            ORDER BY c.id ASC
        ");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name, slug, icon, description)
            VALUES (:name, :slug, :icon, :description)
        ");
        $stmt->execute([
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'icon'        => $data['icon'] ?? 'bi-briefcase',
            'description' => $data['description'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE categories
            SET name = :name, slug = :slug, icon = :icon, description = :description
            WHERE id = :id
        ");
        return $stmt->execute([
            'id'          => $id,
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'icon'        => $data['icon'],
            'description' => $data['description']
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

