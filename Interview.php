<?php

require_once __DIR__ . '/../../config/database.php';

class Interview {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByApplicationId(int $applicationId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM interviews WHERE application_id = :app_id");
        $stmt->execute(['app_id' => $applicationId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function schedule(int $applicationId, string $date, string $type, string $detail, ?string $note = null): bool {
        $existing = $this->findByApplicationId($applicationId);

        if ($existing) {
            $stmt = $this->db->prepare("
                UPDATE interviews
                SET interview_date = :idate, location_type = :ltype, location_detail = :ldetail, note = :note
                WHERE application_id = :app_id
            ");
            return $stmt->execute([
                'app_id'  => $applicationId,
                'idate'   => $date,
                'ltype'   => $type,
                'ldetail' => $detail,
                'note'    => $note
            ]);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO interviews (application_id, interview_date, location_type, location_detail, note)
                VALUES (:app_id, :idate, :ltype, :ldetail, :note)
            ");
            return $stmt->execute([
                'app_id'  => $applicationId,
                'idate'   => $date,
                'ltype'   => $type,
                'ldetail' => $detail,
                'note'    => $note
            ]);
        }
    }

    public function getByCompany(int $companyId): array {
        $stmt = $this->db->prepare("
            SELECT iv.*, 
                   j.title as job_title, 
                   u.name as candidate_name, 
                   u.email as candidate_email,
                   u.phone as candidate_phone,
                   a.cv_path
            FROM interviews iv
            JOIN applications a ON iv.application_id = a.id
            JOIN jobs j ON a.job_id = j.id
            JOIN users u ON a.candidate_id = u.id
            WHERE j.company_id = :company_id
            ORDER BY iv.interview_date ASC
        ");
        $stmt->execute(['company_id' => $companyId]);
        return $stmt->fetchAll();
    }
}


