<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Application.php';
require_once __DIR__ . '/../Models/Notification.php';

class ApiController extends BaseController {
    public function searchJobs(): void {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $categoryId = !empty($_GET['category']) ? (int)$_GET['category'] : null;
        $location = !empty($_GET['location']) ? trim($_GET['location']) : null;
        $skillId = !empty($_GET['skill']) ? (int)$_GET['skill'] : null;
        $status = $_GET['status'] ?? 'open';

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = max(1, min(50, (int)($_GET['limit'] ?? 9)));
        $offset = ($page - 1) * $limit;

        $jobModel = new Job();
        $total = $jobModel->countFiltered($keyword, $categoryId, $location, $status, $skillId);
        $jobs = $jobModel->searchAndFilter($keyword, $categoryId, $location, $status, $skillId, $limit, $offset);
        $totalPages = ceil($total / $limit);

        $this->jsonResponse([
            'success' => true,
            'meta' => [
                'total'        => $total,
                'page'         => $page,
                'limit'        => $limit,
                'total_pages'  => $totalPages,
                'keyword'      => $keyword
            ],
            'data' => $jobs
        ]);
    }

    public function checkApplied(): void {
        $jobId = (int)($_GET['job_id'] ?? 0);
        if ($jobId <= 0) {
            $this->jsonResponse(['success' => false, 'message' => 'Thiếu job_id hợp lệ.'], 400);
        }

        $currentUser = $this->currentUser();
        if (!$currentUser || $currentUser['role'] !== 'student') {
            $this->jsonResponse([
                'success' => true,
                'applied' => false,
                'message' => 'Người dùng chưa đăng nhập hoặc không phải sinh viên.'
            ]);
        }

        $appModel = new Application();
        $existing = $appModel->findByJobAndCandidate($jobId, $currentUser['id']);

        if ($existing) {
            $this->jsonResponse([
                'success'        => true,
                'applied'        => true,
                'application_id' => $existing['id'],
                'status'         => $existing['status'],
                'created_at'     => $existing['created_at']
            ]);
        } else {
            $this->jsonResponse([
                'success' => true,
                'applied' => false
            ]);
        }
    }

    public function markNotificationRead(): void {
        $this->requireAuth();
        $currentUser = $this->currentUser();
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->jsonResponse(['success' => false], 400);
        }

        $notifModel = new Notification();
        $ok = $notifModel->markAsRead($id, $currentUser['id']);

        $this->jsonResponse(['success' => $ok]);
    }
}


