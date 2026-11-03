<?php

require_once __DIR__ . '/../../config/database.php';

class Company {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT c.*, u.email as user_email, u.name as recruiter_name, u.phone as recruiter_phone 
            FROM companies c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $company = $stmt->fetch();
        return $company ?: null;
    }

    public function findByUserId(int $userId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM companies WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $company = $stmt->fetch();
        return $company ?: null;
    }

    public function getAll(?string $status = null): array {
        if ($status) {
            $stmt = $this->db->prepare("
                SELECT c.*, COUNT(j.id) as total_jobs
                FROM companies c
                LEFT JOIN jobs j ON c.id = j.company_id AND j.status = 'open'
                WHERE c.status = :status
                GROUP BY c.id
                ORDER BY c.id DESC
            ");
            $stmt->execute(['status' => $status]);
        } else {
            $stmt = $this->db->query("
                SELECT c.*, COUNT(j.id) as total_jobs, u.email as user_email
                FROM companies c
                JOIN users u ON c.user_id = u.id
                LEFT JOIN jobs j ON c.id = j.company_id
                GROUP BY c.id
                ORDER BY c.id DESC
            ");
        }
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO companies (user_id, name, logo, website, address, city, industry, scale, description, status)
            VALUES (:user_id, :name, :logo, :website, :address, :city, :industry, :scale, :description, :status)
        ");
        $stmt->execute([
            'user_id'     => $data['user_id'],
            'name'        => $data['name'],
            'logo'        => $data['logo'] ?? 'assets/images/company_fpt.svg',
            'website'     => $data['website'] ?? null,
            'address'     => $data['address'] ?? null,
            'city'        => $data['city'] ?? 'Hà Nội',
            'industry'    => $data['industry'] ?? 'Công nghệ thông tin',
            'scale'       => $data['scale'] ?? '50-150 nhân viên',
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? 'active'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE companies 
            SET name = :name, logo = :logo, website = :website, address = :address, 
                city = :city, industry = :industry, scale = :scale, description = :description, status = :status
            WHERE id = :id
        ");
        return $stmt->execute([
            'id'          => $id,
            'name'        => $data['name'],
            'logo'        => $data['logo'],
            'website'     => $data['website'],
            'address'     => $data['address'],
            'city'        => $data['city'],
            'industry'    => $data['industry'],
            'scale'       => $data['scale'],
            'description' => $data['description'],
            'status'      => $data['status'] ?? 'active'
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM companies WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}


