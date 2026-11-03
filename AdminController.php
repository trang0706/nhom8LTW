<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Company.php';
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Skill.php';
require_once __DIR__ . '/../Models/Application.php';

class AdminController extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->requireRole('admin');
    }

    public function dashboard(): void {
        $userModel = new User();
        $companyModel = new Company();
        $jobModel = new Job();
        $appModel = new Application();

        $allUsers = $userModel->getAllUsers();
        $allCompanies = $companyModel->getAll();
        $allJobs = $jobModel->getAllForAdmin();
        $appStats = $appModel->countAllByStatus();

        $studentCount = 0;
        $employerCount = 0;
        foreach ($allUsers as $u) {
            if ($u['role'] === 'student') $studentCount++;
            if ($u['role'] === 'employer') $employerCount++;
        }

        $this->render('admin/dashboard', [
            'pageTitle' => 'Quản Trị Hệ Thống - Administrator Portal',
            'totalUsers' => count($allUsers),
            'studentCount' => $studentCount,
            'employerCount' => $employerCount,
            'totalCompanies' => count($allCompanies),
            'totalJobs' => count($allJobs),
            'appStats' => $appStats,
            'recentJobs' => array_slice($allJobs, 0, 5),
            'recentCompanies' => array_slice($allCompanies, 0, 5)
        ]);
    }

    public function companies(): void {
        $companyModel = new Company();
        $companies = $companyModel->getAll();

        $this->render('admin/companies', [
            'pageTitle' => 'Quản Lý Doanh Nghiệp Tuyển Dụng',
            'companies' => $companies
        ]);
    }

    public function toggleCompanyStatus(): void {
        $id = (int)($_POST['id'] ?? 0);
        $companyModel = new Company();
        $company = $companyModel->findById($id);

        if (!$company) {
            $this->jsonResponse(['success' => false, 'message' => 'Doanh nghiệp không tồn tại.'], 404);
        }

        $newStatus = ($company['status'] === 'active') ? 'locked' : 'active';
        $companyModel->update($id, array_merge($company, ['status' => $newStatus]));

        $this->jsonResponse(['success' => true, 'status' => $newStatus]);
    }

    public function users(): void {
        $role = $_GET['role'] ?? null;
        $userModel = new User();
        $users = $userModel->getAllUsers($role);

        $this->render('admin/users', [
            'pageTitle' => 'Quản Lý Tài Khoản Người Dùng',
            'users' => $users,
            'currentRole' => $role
        ]);
    }

    public function toggleUserStatus(): void {
        $id = (int)($_POST['id'] ?? 0);
        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'Người dùng không tồn tại.'], 404);
        }

        $newStatus = ($user['status'] === 'active') ? 'banned' : 'active';
        $userModel->update($id, ['status' => $newStatus]);

        $this->jsonResponse(['success' => true, 'status' => $newStatus]);
    }

    public function jobs(): void {
        $jobModel = new Job();
        $jobs = $jobModel->getAllForAdmin();

        $this->render('admin/jobs', [
            'pageTitle' => 'Quản Lý Toàn Bộ Tin Tuyển Dụng',
            'jobs' => $jobs
        ]);
    }

    public function deleteJob(): void {
        $id = (int)($_POST['id'] ?? 0);
        $jobModel = new Job();
        $jobModel->delete($id);
        $_SESSION['flash_success'] = 'Đã xóa tin tuyển dụng thành công.';
        $this->redirect('/admin/jobs');
    }

    public function skills(): void {
        $skillModel = new Skill();
        $catModel = new Category();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create_category') {
                $name = trim($_POST['name'] ?? '');
                $slug = trim($_POST['slug'] ?? '');
                $icon = trim($_POST['icon'] ?? 'bi-briefcase');
                $description = trim($_POST['description'] ?? '');
                if (!empty($name)) {
                    if (empty($slug)) {
                        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $name));
                    }
                    $catModel->create([
                        'name' => $name,
                        'slug' => $slug,
                        'icon' => $icon,
                        'description' => $description
                    ]);
                    $_SESSION['flash_success'] = 'Thêm ngành nghề thành công!';
                }
            } elseif ($action === 'create_skill') {
                $name = trim($_POST['name'] ?? '');
                $category = trim($_POST['category'] ?? 'Kỹ thuật');
                if (!empty($name)) {
                    $skillModel->create($name, $category);
                    $_SESSION['flash_success'] = 'Thêm kỹ năng thành công!';
                }
            } elseif ($action === 'delete_category') {
                $catId = (int)($_POST['id'] ?? 0);
                $catModel->delete($catId);
                $_SESSION['flash_success'] = 'Đã xóa ngành nghề.';
            } elseif ($action === 'delete_skill') {
                $skillId = (int)($_POST['id'] ?? 0);
                $skillModel->delete($skillId);
                $_SESSION['flash_success'] = 'Đã xóa kỹ năng.';
            }
            $this->redirect('/admin/skills');
        }

        $categories = $catModel->getAll();
        $skills = $skillModel->getAll();

        $this->render('admin/skills', [
            'pageTitle' => 'Quản Lý Ngành Nghề & Kỹ Năng',
            'categories' => $categories,
            'skills' => $skills
        ]);
    }
}

