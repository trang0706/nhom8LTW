<?php

require_once __DIR__ . '/../../config/database.php';

class Job {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function searchAndFilter(
        ?string $keyword = null,
        ?int $categoryId = null,
        ?string $location = null,
        ?string $status = 'open',
        ?int $skillId = null,
        int $limit = 9,
        int $offset = 0
    ): array {
        $sql = "
            SELECT j.*, 
                   c.name as company_name, 
                   c.logo as company_logo, 
                   c.city as company_city,
                   cat.name as category_name,
                   (CASE WHEN j.deadline < CURDATE() THEN 1 ELSE 0 END) as is_expired
            FROM jobs j
            JOIN companies c ON j.company_id = c.id
            JOIN categories cat ON j.category_id = cat.id
        ";

        if ($skillId) {
            $sql .= " JOIN job_skills js ON j.id = js.job_id AND js.skill_id = :skill_id ";
        }

        $where = [];
        $params = [];

        if ($skillId) {
            $params['skill_id'] = $skillId;
        }

        if ($status !== null && $status !== 'all') {
            $where[] = "j.status = :status";
            $params['status'] = $status;
        }

        if (!empty($keyword)) {
            $where[] = "(j.title LIKE :kw1 OR c.name LIKE :kw2 OR j.description LIKE :kw3 OR j.requirements LIKE :kw4)";
            $kwTerm = '%' . trim($keyword) . '%';
            $params['kw1'] = $kwTerm;
            $params['kw2'] = $kwTerm;
            $params['kw3'] = $kwTerm;
            $params['kw4'] = $kwTerm;
        }

        if (!empty($categoryId)) {
            $where[] = "j.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        if (!empty($location)) {
            $where[] = "j.location LIKE :loc";
            $params['loc'] = '%' . trim($location) . '%';
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY j.id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $jobs = $stmt->fetchAll();

        foreach ($jobs as &$job) {
            $skillStmt = $this->db->prepare("
                SELECT s.id, s.name 
                FROM skills s 
                JOIN job_skills js ON s.id = js.skill_id 
                WHERE js.job_id = :job_id
            ");
            $skillStmt->execute(['job_id' => $job['id']]);
            $job['skills'] = $skillStmt->fetchAll();
        }

        return $jobs;
    }

    public function countFiltered(
        ?string $keyword = null,
        ?int $categoryId = null,
        ?string $location = null,
        ?string $status = 'open',
        ?int $skillId = null
    ): int {
        $sql = "
            SELECT COUNT(DISTINCT j.id) as total
            FROM jobs j
            JOIN companies c ON j.company_id = c.id
            JOIN categories cat ON j.category_id = cat.id
        ";

        if ($skillId) {
            $sql .= " JOIN job_skills js ON j.id = js.job_id AND js.skill_id = :skill_id ";
        }

        $where = [];
        $params = [];

        if ($skillId) {
            $params['skill_id'] = $skillId;
        }

        if ($status !== null && $status !== 'all') {
            $where[] = "j.status = :status";
            $params['status'] = $status;
        }

        if (!empty($keyword)) {
            $where[] = "(j.title LIKE :kw1 OR c.name LIKE :kw2 OR j.description LIKE :kw3 OR j.requirements LIKE :kw4)";
            $kwTerm = '%' . trim($keyword) . '%';
            $params['kw1'] = $kwTerm;
            $params['kw2'] = $kwTerm;
            $params['kw3'] = $kwTerm;
            $params['kw4'] = $kwTerm;
        }

        if (!empty($categoryId)) {
            $where[] = "j.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        if (!empty($location)) {
            $where[] = "j.location LIKE :loc";
            $params['loc'] = '%' . trim($location) . '%';
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }
        $stmt->execute();
        $row = $stmt->fetch();
        return (int)($row['total'] ?? 0);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT j.*, 
                   c.name as company_name, 
                   c.logo as company_logo, 
                   c.website as company_website,
                   c.address as company_address,
                   c.city as company_city,
                   c.scale as company_scale,
                   c.description as company_description,
                   c.user_id as company_user_id,
                   cat.name as category_name,
                   (CASE WHEN j.deadline < CURDATE() THEN 1 ELSE 0 END) as is_expired
            FROM jobs j
            JOIN companies c ON j.company_id = c.id
            JOIN categories cat ON j.category_id = cat.id
            WHERE j.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $job = $stmt->fetch();

        if ($job) {
            $skillStmt = $this->db->prepare("
                SELECT s.id, s.name, s.category 
                FROM skills s 
                JOIN job_skills js ON s.id = js.skill_id 
                WHERE js.job_id = :job_id
            ");
            $skillStmt->execute(['job_id' => $job['id']]);
            $job['skills'] = $skillStmt->fetchAll();
            return $job;
        }

        return null;
    }

    public function getByCompany(int $companyId): array {
        $stmt = $this->db->prepare("
            SELECT j.*, 
                   cat.name as category_name,
                   COUNT(a.id) as total_applications,
                   SUM(CASE WHEN a.status = 'submitted' THEN 1 ELSE 0 END) as submitted_count,
                   SUM(CASE WHEN a.status = 'reviewing' THEN 1 ELSE 0 END) as reviewing_count,
                   SUM(CASE WHEN a.status = 'interview' THEN 1 ELSE 0 END) as interview_count,
                   SUM(CASE WHEN a.status = 'accepted' THEN 1 ELSE 0 END) as accepted_count,
                   SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected_count,
                   (CASE WHEN j.deadline < CURDATE() THEN 1 ELSE 0 END) as is_expired
            FROM jobs j
            JOIN categories cat ON j.category_id = cat.id
            LEFT JOIN applications a ON j.id = a.job_id
            WHERE j.company_id = :company_id
            GROUP BY j.id
            ORDER BY j.id DESC
        ");
        $stmt->execute(['company_id' => $companyId]);
        return $stmt->fetchAll();
    }

    public function getAllForAdmin(): array {
        $stmt = $this->db->query("
            SELECT j.*, 
                   c.name as company_name, 
                   cat.name as category_name,
                   COUNT(a.id) as total_applications,
                   (CASE WHEN j.deadline < CURDATE() THEN 1 ELSE 0 END) as is_expired
            FROM jobs j
            JOIN companies c ON j.company_id = c.id
            JOIN categories cat ON j.category_id = cat.id
            LEFT JOIN applications a ON j.id = a.job_id
            GROUP BY j.id
            ORDER BY j.id DESC
        ");
        return $stmt->fetchAll();
    }

    public function create(array $data, array $skillIds = []): int {
        $slug = $this->generateSlug($data['title']) . '-' . time();
        $stmt = $this->db->prepare("
            INSERT INTO jobs (company_id, category_id, title, slug, location, work_type, allowance, quantity, deadline, description, requirements, benefits, status)
            VALUES (:company_id, :category_id, :title, :slug, :location, :work_type, :allowance, :quantity, :deadline, :description, :requirements, :benefits, :status)
        ");
        $stmt->execute([
            'company_id'   => $data['company_id'],
            'category_id'  => $data['category_id'],
            'title'        => $data['title'],
            'slug'         => $slug,
            'location'     => $data['location'],
            'work_type'    => $data['work_type'] ?? 'Full-time',
            'allowance'    => $data['allowance'] ?? 'Thỏa thuận',
            'quantity'     => $data['quantity'] ?? 1,
            'deadline'     => $data['deadline'],
            'description'  => $data['description'],
            'requirements' => $data['requirements'],
            'benefits'     => $data['benefits'],
            'status'       => $data['status'] ?? 'open'
        ]);
        $jobId = (int)$this->db->lastInsertId();

        if (!empty($skillIds)) {
            $skillModel = new Skill();
            $skillModel->syncJobSkills($jobId, $skillIds);
        }

        return $jobId;
    }

    public function update(int $id, array $data, array $skillIds = []): bool {
        $stmt = $this->db->prepare("
            UPDATE jobs
            SET category_id = :category_id, title = :title, location = :location,
                work_type = :work_type, allowance = :allowance, quantity = :quantity,
                deadline = :deadline, description = :description, requirements = :requirements,
                benefits = :benefits, status = :status
            WHERE id = :id
        ");
        $ok = $stmt->execute([
            'id'           => $id,
            'category_id'  => $data['category_id'],
            'title'        => $data['title'],
            'location'     => $data['location'],
            'work_type'    => $data['work_type'],
            'allowance'    => $data['allowance'],
            'quantity'     => $data['quantity'],
            'deadline'     => $data['deadline'],
            'description'  => $data['description'],
            'requirements' => $data['requirements'],
            'benefits'     => $data['benefits'],
            'status'       => $data['status']
        ]);

        if ($ok && !empty($skillIds)) {
            $skillModel = new Skill();
            $skillModel->syncJobSkills($id, $skillIds);
        }

        return $ok;
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE jobs SET status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function incrementViews(int $id): void {
        $stmt = $this->db->prepare("UPDATE jobs SET views = views + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM jobs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getRelated(int $categoryId, int $excludeId, int $limit = 3): array {
        $stmt = $this->db->prepare("
            SELECT j.*, c.name as company_name, c.logo as company_logo
            FROM jobs j
            JOIN companies c ON j.company_id = c.id
            WHERE j.category_id = :cat_id AND j.id != :exclude_id AND j.status = 'open' AND j.deadline >= CURDATE()
            ORDER BY j.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFeaturedJobs(int $limit = 6): array {
        $stmt = $this->db->prepare("
            SELECT j.*, c.name as company_name, c.logo as company_logo, cat.name as category_name
            FROM jobs j
            JOIN companies c ON j.company_id = c.id
            JOIN categories cat ON j.category_id = cat.id
            WHERE j.status = 'open' AND j.deadline >= CURDATE()
            ORDER BY j.views DESC, j.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $jobs = $stmt->fetchAll();

        foreach ($jobs as &$job) {
            $skillStmt = $this->db->prepare("
                SELECT s.id, s.name 
                FROM skills s 
                JOIN job_skills js ON s.id = js.skill_id 
                WHERE js.job_id = :job_id
            ");
            $skillStmt->execute(['job_id' => $job['id']]);
            $job['skills'] = $skillStmt->fetchAll();
        }

        return $jobs;
    }

    private function generateSlug(string $str): string {
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return trim($str, '-');
    }
}


