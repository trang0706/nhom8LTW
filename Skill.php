<?php

require_once __DIR__ . '/../../config/database.php';

class Skill {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM skills ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getByJobId(int $jobId): array {
        $stmt = $this->db->prepare("
            SELECT s.*
            FROM skills s
            JOIN job_skills js ON s.id = js.skill_id
            WHERE js.job_id = :job_id
            ORDER BY s.name ASC
        ");
        $stmt->execute(['job_id' => $jobId]);
        return $stmt->fetchAll();
    }

    public function syncJobSkills(int $jobId, array $skillIds): void {
        $stmtDel = $this->db->prepare("DELETE FROM job_skills WHERE job_id = :job_id");
        $stmtDel->execute(['job_id' => $jobId]);

        if (!empty($skillIds)) {
            $stmtIns = $this->db->prepare("INSERT INTO job_skills (job_id, skill_id) VALUES (:job_id, :skill_id)");
            foreach ($skillIds as $sid) {
                $stmtIns->execute(['job_id' => $jobId, 'skill_id' => (int)$sid]);
            }
        }
    }

    public function create(string $name, string $category = 'Kỹ thuật'): int {
        $stmt = $this->db->prepare("INSERT INTO skills (name, category) VALUES (:name, :category)");
        $stmt->execute(['name' => $name, 'category' => $category]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM skills WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}


