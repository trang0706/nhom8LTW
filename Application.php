<?php

require_once __DIR__ . '/../../config/database.php';

class Application {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT a.*, 
                   j.title as job_title, 
                   j.deadline as job_deadline,
                   j.status as job_status,
                   j.company_id,
                   c.name as company_name, 
                   c.logo as company_logo,
                   c.user_id as employer_user_id,
                   u.name as candidate_name, 
                   u.email as candidate_email, 
                   u.phone as candidate_phone,
                   sp.university,
                   sp.major,
                   sp.gpa,
                   sp.skills as student_skills,
                   iv.interview_date,
                   iv.location_type as interview_type,
                   iv.location_detail as interview_detail,
                   iv.note as interview_note
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN companies c ON j.company_id = c.id
            JOIN users u ON a.candidate_id = u.id
            LEFT JOIN student_profiles sp ON u.id = sp.user_id
            LEFT JOIN interviews iv ON a.id = iv.application_id
            WHERE a.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByJobAndCandidate(int $jobId, int $candidateId): ?array {
        $stmt = $this->db->prepare("
            SELECT * FROM applications 
            WHERE job_id = :job_id AND candidate_id = :candidate_id
        ");
        $stmt->execute(['job_id' => $jobId, 'candidate_id' => $candidateId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO applications (job_id, candidate_id, cover_letter, cv_path, cv_filename, status)
            VALUES (:job_id, :candidate_id, :cover_letter, :cv_path, :cv_filename, 'submitted')
        ");
        $stmt->execute([
            'job_id'       => $data['job_id'],
            'candidate_id' => $data['candidate_id'],
            'cover_letter' => $data['cover_letter'] ?? null,
            'cv_path'      => $data['cv_path'],
            'cv_filename'  => $data['cv_filename']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getByCandidate(int $candidateId): array {
        $stmt = $this->db->prepare("
            SELECT a.*, 
                   j.title as job_title, 
                   j.slug as job_slug,
                   j.location as job_location,
                   j.allowance as job_allowance,
                   j.deadline as job_deadline,
                   c.name as company_name, 
                   c.logo as company_logo,
                   iv.interview_date,
                   iv.location_type as interview_type,
                   iv.location_detail as interview_detail,
                   iv.note as interview_note
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN companies c ON j.company_id = c.id
            LEFT JOIN interviews iv ON a.id = iv.application_id
            WHERE a.candidate_id = :candidate_id
            ORDER BY a.id DESC
        ");
        $stmt->execute(['candidate_id' => $candidateId]);
        return $stmt->fetchAll();
    }

    public function getByCompany(int $companyId, ?string $status = null, ?int $jobId = null): array {
        $sql = "
            SELECT a.*, 
                   j.title as job_title, 
                   u.name as candidate_name, 
                   u.email as candidate_email, 
                   u.phone as candidate_phone,
                   sp.university,
                   sp.major,
                   sp.gpa,
                   iv.interview_date,
                   iv.location_type as interview_type,
                   iv.location_detail as interview_detail
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN users u ON a.candidate_id = u.id
            LEFT JOIN student_profiles sp ON u.id = sp.user_id
            LEFT JOIN interviews iv ON a.id = iv.application_id
            WHERE j.company_id = :company_id
        ";

        $params = ['company_id' => $companyId];

        if ($status && $status !== 'all') {
            $sql .= " AND a.status = :status";
            $params['status'] = $status;
        }

        if ($jobId) {
            $sql .= " AND a.job_id = :job_id";
            $params['job_id'] = $jobId;
        }

        $sql .= " ORDER BY a.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status, ?string $note = null): bool {
        $stmt = $this->db->prepare("
            UPDATE applications
            SET status = :status, employer_note = :note, updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            'id'     => $id,
            'status' => $status,
            'note'   => $note
        ]);
    }

    public function countByCompanyAndStatus(int $companyId): array {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(a.id) as total,
                SUM(CASE WHEN a.status = 'submitted' THEN 1 ELSE 0 END) as submitted,
                SUM(CASE WHEN a.status = 'reviewing' THEN 1 ELSE 0 END) as reviewing,
                SUM(CASE WHEN a.status = 'interview' THEN 1 ELSE 0 END) as interview,
                SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            WHERE j.company_id = :company_id
        ");
        $stmt->execute(['company_id' => $companyId]);
        return $stmt->fetch() ?: [
            'total' => 0, 'submitted' => 0, 'reviewing' => 0, 'interview' => 0, 'accepted' => 0, 'rejected' => 0
        ];
    }

    public function countAllByStatus(): array {
        $stmt = $this->db->query("
            SELECT 
                COUNT(id) as total,
                SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted,
                SUM(CASE WHEN status = 'reviewing' THEN 1 ELSE 0 END) as reviewing,
                SUM(CASE WHEN status = 'interview' THEN 1 ELSE 0 END) as interview,
                SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
            FROM applications
        ");
        return $stmt->fetch() ?: [
            'total' => 0, 'submitted' => 0, 'reviewing' => 0, 'interview' => 0, 'accepted' => 0, 'rejected' => 0
        ];
    }
}

