<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Skill.php';
require_once __DIR__ . '/../Models/Application.php';
require_once __DIR__ . '/../Models/StudentProfile.php';

class JobController extends BaseController {
    public function index(): void {
        $jobModel = new Job();
        $catModel = new Category();
        $skillModel = new Skill();

        $keyword = isset($_GET['q']) ? trim($_GET['q']) : null;
        $categoryId = !empty($_GET['category']) ? (int)$_GET['category'] : null;
        $location = !empty($_GET['location']) ? trim($_GET['location']) : null;
        $skillId = !empty($_GET['skill']) ? (int)$_GET['skill'] : null;
        $status = $_GET['status'] ?? 'open';

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 9;
        $offset = ($page - 1) * $limit;

        $totalJobs = $jobModel->countFiltered($keyword, $categoryId, $location, $status, $skillId);
        $jobs = $jobModel->searchAndFilter($keyword, $categoryId, $location, $status, $skillId, $limit, $offset);
        $totalPages = ceil($totalJobs / $limit);

        $categories = $catModel->getAll();
        $skills = $skillModel->getAll();

        $this->render('jobs/index', [
            'pageTitle' => 'Khám Phá Vị Trí Thực Tập Doanh Nghiệp',
            'jobs' => $jobs,
            'totalJobs' => $totalJobs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'skills' => $skills,
            'filters' => [
                'q' => $keyword,
                'category' => $categoryId,
                'location' => $location,
                'skill' => $skillId,
                'status' => $status
            ]
        ]);
    }

    public function detail(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/jobs');
        }

        $jobModel = new Job();
        $job = $jobModel->findById($id);

        if (!$job) {
            $_SESSION['flash_error'] = 'Không tìm thấy thông tin vị trí thực tập này.';
            $this->redirect('/jobs');
        }

        $jobModel->incrementViews($id);

        $hasApplied = false;
        $existingApplication = null;
        $studentProfile = null;

        $user = $this->currentUser();
        if ($user && $user['role'] === 'student') {
            $appModel = new Application();
            $existingApplication = $appModel->findByJobAndCandidate($id, $user['id']);
            $hasApplied = !empty($existingApplication);

            $profileModel = new StudentProfile();
            $studentProfile = $profileModel->findByUserId($user['id']);
        }

        $relatedJobs = $jobModel->getRelated((int)$job['category_id'], $id, 3);

        $this->render('jobs/detail', [
            'pageTitle' => $job['title'] . ' - ' . $job['company_name'],
            'job' => $job,
            'hasApplied' => $hasApplied,
            'existingApplication' => $existingApplication,
            'studentProfile' => $studentProfile,
            'relatedJobs' => $relatedJobs
        ]);
    }
}


