<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Company.php';
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Skill.php';
require_once __DIR__ . '/../Models/Application.php';
require_once __DIR__ . '/../Models/Interview.php';
require_once __DIR__ . '/../Models/Notification.php';

class EmployerController extends BaseController {
    private ?array $company = null;

    public function __construct() {
        parent::__construct();
        $this->requireRole('employer');
        $currentUser = $this->currentUser();
        $companyModel = new Company();
        $this->company = $companyModel->findByUserId($currentUser['id']);

        if (!$this->company) {
            $companyId = $companyModel->create([
                'user_id' => $currentUser['id'],
                'name' => $currentUser['name'] . ' Company',
                'logo' => 'assets/images/company_fpt.svg',
                'city' => 'Hà Nội'
            ]);
            $this->company = $companyModel->findById($companyId);
        }
    }

    public function dashboard(): void {
        $jobModel = new Job();
        $appModel = new Application();

        $companyJobs = $jobModel->getByCompany($this->company['id']);
        $stats = $appModel->countByCompanyAndStatus($this->company['id']);

        $openJobsCount = 0;
        foreach ($companyJobs as $j) {
            if ($j['status'] === 'open' && !$j['is_expired']) {
                $openJobsCount++;
            }
        }

        $recentApplications = $appModel->getByCompany($this->company['id']);
        $recentApplications = array_slice($recentApplications, 0, 5);

        $this->render('employer/dashboard', [
            'pageTitle' => 'Dashboard Nhà Tuyển Dụng - ' . $this->company['name'],
            'company' => $this->company,
            'companyJobs' => $companyJobs,
            'stats' => $stats,
            'openJobsCount' => $openJobsCount,
            'recentApplications' => $recentApplications
        ]);
    }

    public function jobs(): void {
        $jobModel = new Job();
        $companyJobs = $jobModel->getByCompany($this->company['id']);

        $this->render('employer/jobs', [
            'pageTitle' => 'Quản Trị Tin Tuyển Dụng',
            'company' => $this->company,
            'jobs' => $companyJobs
        ]);
    }

    public function createJob(): void {
        $catModel = new Category();
        $skillModel = new Skill();

        $categories = $catModel->getAll();
        $skills = $skillModel->getAll();

        $this->render('employer/job_form', [
            'pageTitle' => 'Đăng Tin Thực Tập Mới',
            'company' => $this->company,
            'job' => null,
            'categories' => $categories,
            'skills' => $skills,
            'selectedSkills' => []
        ]);
    }

    public function storeJob(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/employer/jobs');
        }

        $title = trim($_POST['title'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $location = trim($_POST['location'] ?? '');
        $workType = $_POST['work_type'] ?? 'Full-time';
        $allowance = trim($_POST['allowance'] ?? 'Thỏa thuận');
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));
        $deadline = $_POST['deadline'] ?? date('Y-m-d', strtotime('+30 days'));
        $description = trim($_POST['description'] ?? '');
        $requirements = trim($_POST['requirements'] ?? '');
        $benefits = trim($_POST['benefits'] ?? '');
        $status = $_POST['status'] ?? 'open';
        $skillIds = $_POST['skills'] ?? [];

        if (empty($title) || empty($categoryId) || empty($location) || empty($deadline)) {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ các thông tin bắt buộc.';
            $this->redirect('/employer/jobs/create');
        }

        $jobModel = new Job();
        $jobId = $jobModel->create([
            'company_id'   => $this->company['id'],
            'category_id'  => $categoryId,
            'title'        => $title,
            'location'     => $location,
            'work_type'    => $workType,
            'allowance'    => $allowance,
            'quantity'     => $quantity,
            'deadline'     => $deadline,
            'description'  => $description,
            'requirements' => $requirements,
            'benefits'     => $benefits,
            'status'       => $status
        ], $skillIds);

        $_SESSION['flash_success'] = 'Đăng tin tuyển dụng thực tập thành công!';
        $this->redirect('/employer/jobs');
    }

    public function editJob(): void {
        $id = (int)($_GET['id'] ?? 0);
        $jobModel = new Job();
        $job = $jobModel->findById($id);

        if (!$job || $job['company_id'] != $this->company['id']) {
            $_SESSION['flash_error'] = 'Bạn không có quyền chỉnh sửa tin tuyển dụng này.';
            $this->redirect('/employer/jobs');
        }

        $catModel = new Category();
        $skillModel = new Skill();

        $categories = $catModel->getAll();
        $skills = $skillModel->getAll();
        $selectedSkills = array_column($job['skills'] ?? [], 'id');

        $this->render('employer/job_form', [
            'pageTitle' => 'Chỉnh Sửa Tin Tuyển Dụng',
            'company' => $this->company,
            'job' => $job,
            'categories' => $categories,
            'skills' => $skills,
            'selectedSkills' => $selectedSkills
        ]);
    }

    public function updateJob(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/employer/jobs');
        }

        $id = (int)($_POST['id'] ?? 0);
        $jobModel = new Job();
        $job = $jobModel->findById($id);

        if (!$job || $job['company_id'] != $this->company['id']) {
            $_SESSION['flash_error'] = 'Bạn không có quyền cập nhật tin tuyển dụng này.';
            $this->redirect('/employer/jobs');
        }

        $title = trim($_POST['title'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $location = trim($_POST['location'] ?? '');
        $workType = $_POST['work_type'] ?? 'Full-time';
        $allowance = trim($_POST['allowance'] ?? 'Thỏa thuận');
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));
        $deadline = $_POST['deadline'] ?? date('Y-m-d');
        $description = trim($_POST['description'] ?? '');
        $requirements = trim($_POST['requirements'] ?? '');
        $benefits = trim($_POST['benefits'] ?? '');
        $status = $_POST['status'] ?? 'open';
        $skillIds = $_POST['skills'] ?? [];

        $jobModel->update($id, [
            'category_id'  => $categoryId,
            'title'        => $title,
            'location'     => $location,
            'work_type'    => $workType,
            'allowance'    => $allowance,
            'quantity'     => $quantity,
            'deadline'     => $deadline,
            'description'  => $description,
            'requirements' => $requirements,
            'benefits'     => $benefits,
            'status'       => $status
        ], $skillIds);

        $_SESSION['flash_success'] = 'Cập nhật tin tuyển dụng thành công!';
        $this->redirect('/employer/jobs');
    }

    public function toggleJobStatus(): void {
        $id = (int)($_POST['id'] ?? 0);
        $jobModel = new Job();
        $job = $jobModel->findById($id);

        if (!$job || $job['company_id'] != $this->company['id']) {
            $this->jsonResponse(['success' => false, 'message' => 'Không có quyền thao tác.'], 403);
        }

        $newStatus = ($job['status'] === 'open') ? 'closed' : 'open';
        $jobModel->updateStatus($id, $newStatus);

        $this->jsonResponse(['success' => true, 'status' => $newStatus, 'message' => 'Cập nhật trạng thái tin thành công!']);
    }

    public function deleteJob(): void {
        $id = (int)($_POST['id'] ?? 0);
        $jobModel = new Job();
        $job = $jobModel->findById($id);

        if (!$job || $job['company_id'] != $this->company['id']) {
            $_SESSION['flash_error'] = 'Bạn không có quyền xóa tin tuyển dụng này.';
            $this->redirect('/employer/jobs');
        }

        $jobModel->delete($id);
        $_SESSION['flash_success'] = 'Đã xóa tin tuyển dụng thành công.';
        $this->redirect('/employer/jobs');
    }

    public function candidates(): void {
        $jobId = !empty($_GET['job_id']) ? (int)$_GET['job_id'] : null;
        $status = $_GET['status'] ?? null;

        $jobModel = new Job();
        $companyJobs = $jobModel->getByCompany($this->company['id']);

        if ($jobId) {
            $currentJob = $jobModel->findById($jobId);
            if (!$currentJob || $currentJob['company_id'] != $this->company['id']) {
                http_response_code(403);
                die("403 Forbidden - Bạn không có quyền truy cập hồ sơ của tin tuyển dụng này.");
            }
        }

        $appModel = new Application();
        $applications = $appModel->getByCompany($this->company['id'], $status, $jobId);

        $this->render('employer/candidates', [
            'pageTitle' => 'Quản Lý Hồ Sơ Ứng Viên Thực Tập',
            'company' => $this->company,
            'jobs' => $companyJobs,
            'applications' => $applications,
            'selectedJobId' => $jobId,
            'selectedStatus' => $status
        ]);
    }

    public function updateStatus(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/employer/candidates');
        }

        $appId = (int)($_POST['application_id'] ?? 0);
        $status = $_POST['status'] ?? 'submitted';
        $note = trim($_POST['employer_note'] ?? '');

        $appModel = new Application();
        $app = $appModel->findById($appId);

        if (!$app || $app['company_id'] != $this->company['id']) {
            http_response_code(403);
            die("403 Forbidden - Thao tác bị từ chối do không đủ quyền hoặc hồ sơ thuộc doanh nghiệp khác.");
        }

        $allowedStatuses = ['submitted', 'reviewing', 'interview', 'accepted', 'rejected'];
        if (!in_array($status, $allowedStatuses)) {
            $_SESSION['flash_error'] = 'Trạng thái cập nhật không hợp lệ.';
            $this->redirect('/employer/candidates');
        }

        $appModel->updateStatus($appId, $status, $note);

        if ($status === 'interview') {
            $interviewDate = $_POST['interview_date'] ?? null;
            $locationType = $_POST['location_type'] ?? 'online';
            $locationDetail = trim($_POST['location_detail'] ?? '');
            $interviewNote = trim($_POST['interview_note'] ?? '');

            if (!empty($interviewDate) && !empty($locationDetail)) {
                $ivModel = new Interview();
                $ivModel->schedule($appId, $interviewDate, $locationType, $locationDetail, $interviewNote);
            }
        }

        $statusLabels = [
            'submitted' => 'Đã nộp',
            'reviewing' => 'Đang xét duyệt',
            'interview' => 'Mời phỏng vấn',
            'accepted'  => 'Trúng tuyển thực tập',
            'rejected'  => 'Từ chối hồ sơ'
        ];

        $statusText = $statusLabels[$status] ?? $status;
        $notifModel = new Notification();
        $notifModel->create(
            (int)$app['candidate_id'],
            'Cập nhật trạng thái hồ sơ ứng tuyển',
            "Nhà tuyển dụng {$this->company['name']} đã chuyển trạng thái hồ sơ vị trí '{$app['job_title']}' sang: {$statusText}.",
            '/applications/history'
        );

        $_SESSION['flash_success'] = 'Cập nhật trạng thái hồ sơ ứng viên thành công!';
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/employer/candidates');
    }

    public function interviews(): void {
        $ivModel = new Interview();
        $interviews = $ivModel->getByCompany($this->company['id']);

        $this->render('employer/interviews', [
            'pageTitle' => 'Lịch Phỏng Vấn Thực Tập Sinh',
            'company' => $this->company,
            'interviews' => $interviews
        ]);
    }

    public function exportCsv(): void {
        $jobId = !empty($_GET['job_id']) ? (int)$_GET['job_id'] : null;

        if ($jobId) {
            $jobModel = new Job();
            $job = $jobModel->findById($jobId);
            if (!$job || $job['company_id'] != $this->company['id']) {
                http_response_code(403);
                die("403 Forbidden - Bạn không có quyền xuất dữ liệu này.");
            }
        }

        $appModel = new Application();
        $candidates = $appModel->getByCompany($this->company['id'], null, $jobId);

        $filename = 'Danh_sach_ung_vien_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, ['ID', 'Họ và tên', 'Email', 'Số điện thoại', 'Trường Đại học', 'Chuyên ngành', 'GPA', 'Vị trí ứng tuyển', 'Trạng thái', 'Ghi chú', 'Ngày nộp']);

        $statusLabels = [
            'submitted' => 'Đã nộp',
            'reviewing' => 'Đang xét',
            'interview' => 'Phỏng vấn',
            'accepted'  => 'Đạt',
            'rejected'  => 'Từ chối'
        ];

        foreach ($candidates as $c) {
            fputcsv($output, [
                $c['id'],
                $c['candidate_name'],
                $c['candidate_email'],
                $c['candidate_phone'] ?? 'Chưa cập nhật',
                $c['university'] ?? 'Chưa cập nhật',
                $c['major'] ?? 'Chưa cập nhật',
                $c['gpa'] ?? 'N/A',
                $c['job_title'],
                $statusLabels[$c['status']] ?? $c['status'],
                $c['employer_note'] ?? '',
                $c['created_at']
            ]);
        }

        fclose($output);
        exit;
    }
}


