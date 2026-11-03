<?php

require_once __DIR__ . '/../../config/database.php';

class StudentProfile {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByUserId(int $userId): ?array {
        $stmt = $this->db->prepare("
            SELECT sp.*, u.name, u.email, u.phone, u.avatar
            FROM student_profiles sp
            JOIN users u ON sp.user_id = u.id
            WHERE sp.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $profile = $stmt->fetch();
        return $profile ?: null;
    }

    public function save(int $userId, array $data): bool {
        $existing = $this->findByUserId($userId);

        if ($existing) {
            $stmt = $this->db->prepare("
                UPDATE student_profiles
                SET university = :university, major = :major, student_code = :student_code,
                    gpa = :gpa, bio = :bio, skills = :skills, cv_path = :cv_path
                WHERE user_id = :user_id
            ");
            return $stmt->execute([
                'user_id'      => $userId,
                'university'   => $data['university'] ?? '',
                'major'        => $data['major'] ?? '',
                'student_code' => $data['student_code'] ?? '',
                'gpa'          => $data['gpa'] ?? null,
                'bio'          => $data['bio'] ?? '',
                'skills'       => $data['skills'] ?? '',
                'cv_path'      => $data['cv_path'] ?? $existing['cv_path']
            ]);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO student_profiles (user_id, university, major, student_code, gpa, bio, skills, cv_path)
                VALUES (:user_id, :university, :major, :student_code, :gpa, :bio, :skills, :cv_path)
            ");
            return $stmt->execute([
                'user_id'      => $userId,
                'university'   => $data['university'] ?? '',
                'major'        => $data['major'] ?? '',
                'student_code' => $data['student_code'] ?? '',
                'gpa'          => $data['gpa'] ?? null,
                'bio'          => $data['bio'] ?? '',
                'skills'       => $data['skills'] ?? '',
                'cv_path'      => $data['cv_path'] ?? null
            ]);
        }
    }
}


