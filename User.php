<?php

require_once __DIR__ . '/../../config/database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role, phone, avatar, status)
            VALUES (:name, :email, :password, :role, :phone, :avatar, :status)
        ");
        $stmt->execute([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'     => $data['role'] ?? 'student',
            'phone'    => $data['phone'] ?? null,
            'avatar'   => $data['avatar'] ?? 'assets/images/avatar_student1.svg',
            'status'   => $data['status'] ?? 'active'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params['name'] = $data['name'];
        }
        if (isset($data['phone'])) {
            $fields[] = 'phone = :phone';
            $params['phone'] = $data['phone'];
        }
        if (isset($data['avatar'])) {
            $fields[] = 'avatar = :avatar';
            $params['avatar'] = $data['avatar'];
        }
        if (isset($data['status'])) {
            $fields[] = 'status = :status';
            $params['status'] = $data['status'];
        }
        if (!empty($data['password'])) {
            $fields[] = 'password = :password';
            $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getAllUsers(?string $role = null): array {
        if ($role) {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE role = :role ORDER BY id DESC");
            $stmt->execute(['role' => $role]);
        } else {
            $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        }
        return $stmt->fetchAll();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

