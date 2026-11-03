<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Application.php';
require_once __DIR__ . '/../Models/StudentProfile.php';
require_once __DIR__ . '/../Models/Notification.php';

class ApplicationController extends BaseController {
    public function apply(): void {
        $this->requireRole('student');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/jobs');
        }

        $jobId = (int)($_POST['job_id'] ?? 0);
        $coverLetter = trim($_POST['cover_letter'] ?? '');
        $useExistingCv = isset($_POST['use_existing_cv']) && $_POST['use_existing_cv'] === '1';

        $jobModel = new Job();
        $job = $jobModel->findById($jobId);

        if (!$job) {
            $_SESSION['flash_error'] = 'Vị trí thực tập không tồn tại.';
            $this->redirect('/jobs');
        }

        $today = date('Y-m-d');
        if ($job['status'] !== 'open' || $job['deadline'] < $today) {
            $_SESSION['flash_error'] = 'Tin tuyển dụng đã đóng hoặc đã quá hạn nộp hồ sơ.';
            $this->redirect('/jobs/detail?id=' . $jobId);
        }

        $currentUser = $this->currentUser();
        $candidateId = $currentUser['id'];

        $appModel = new Application();
        $existing = $appModel->findByJobAndCandidate($jobId, $candidateId);
        if ($existing) {
            $_SESSION['flash_error'] = 'Bạn đã nộp hồ sơ ứng tuyển vào vị trí này rồi. Vui lòng theo dõi trạng thái tại Lịch sử ứng tuyển.';
            $this->redirect('/applications/history');
        }

        $cvPath = '';
        $cvFilename = '';

        $profileModel = new StudentProfile();
        $profile = $profileModel->findByUserId($candidateId);

        if ($useExistingCv && !empty($profile['cv_path']) && file_exists(__DIR__ . '/../../' . $profile['cv_path'])) {
            $cvPath = $profile['cv_path'];
            $cvFilename = basename($profile['cv_path']);
        } else {
            if (!isset($_FILES['cv_file']) || $_FILES['cv_file']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['flash_error'] = 'Vui lòng đính kèm file CV của bạn.';
                $this->redirect('/jobs/detail?id=' . $jobId);
            }

            $file = $_FILES['cv_file'];
            $allowedExtensions = ['pdf', 'doc', 'docx'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedExtensions)) {
                $_SESSION['flash_error'] = 'Định dạng file CV không hợp lệ. Hệ thống chỉ chấp nhận định dạng PDF, DOC, DOCX.';
                $this->redirect('/jobs/detail?id=' . $jobId);
            }

            if ($file['size'] > 5 * 1024 * 1024) {
                $_SESSION['flash_error'] = 'Dung lượng file CV vượt quá giới hạn cho phép (tối đa 5MB).';
                $this->redirect('/jobs/detail?id=' . $jobId);
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            $allowedMimes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/octet-stream'
            ];

            if (!in_array($mimeType, $allowedMimes)) {
                $_SESSION['flash_error'] = 'Tập tin CV bị từ chối do không an toàn hoặc không đúng định dạng tài liệu.';
                $this->redirect('/jobs/detail?id=' . $jobId);
            }

            $uploadDir = __DIR__ . '/../../storage/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $safeFileName = 'CV_' . $candidateId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $fileExt;
            $destination = $uploadDir . $safeFileName;

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi lưu file CV. Vui lòng thử lại.';
                $this->redirect('/jobs/detail?id=' . $jobId);
            }

            $cvPath = 'storage/uploads/' . $safeFileName;
            $cvFilename = $file['name'];

            if (!empty($_POST['save_as_default_cv'])) {
                $profileModel->save($candidateId, [
                    'cv_path' => $cvPath
                ]);
            }
        }

        $appId = $appModel->create([
            'job_id'       => $jobId,
            'candidate_id' => $candidateId,
            'cover_letter' => $coverLetter,
            'cv_path'      => $cvPath,
            'cv_filename'  => $cvFilename
        ]);

        if ($appId) {
            $notifModel = new Notification();
            $notifModel->create(
                (int)$job['company_user_id'],
                'Hồ sơ ứng tuyển mới',
                "Ứng viên {$currentUser['name']} vừa nộp hồ sơ vào vị trí '{$job['title']}'.",
                "/employer/candidates?job_id=" . $jobId
            );

            $_SESSION['flash_success'] = 'Nộp hồ sơ ứng tuyển thành công! Nhà tuyển dụng sẽ xem xét hồ sơ của bạn.';
            $this->redirect('/applications/history');
        } else {
            $_SESSION['flash_error'] = 'Không thể gửi hồ sơ ứng tuyển. Vui lòng thử lại sau.';
            $this->redirect('/jobs/detail?id=' . $jobId);
        }
    }

    public function history(): void {
        $this->requireRole('student');
        $currentUser = $this->currentUser();

        $appModel = new Application();
        $applications = $appModel->getByCandidate($currentUser['id']);

        $this->render('applications/history', [
            'pageTitle' => 'Lịch Sử Ứng Tuyển & Trạng Thái Hồ Sơ',
            'applications' => $applications
        ]);
    }

    public function downloadCv(): void {
        $this->requireAuth();
        $currentUser = $this->currentUser();

        $appId = (int)($_GET['id'] ?? 0);
        if ($appId <= 0) {
            die("Tham số không hợp lệ.");
        }

        $appModel = new Application();
        $application = $appModel->findById($appId);

        if (!$application) {
            die("Không tìm thấy hồ sơ ứng tuyển.");
        }

        $isCandidate = ($currentUser['role'] === 'student' && $currentUser['id'] == $application['candidate_id']);
        $isEmployer = ($currentUser['role'] === 'employer' && $currentUser['id'] == $application['employer_user_id']);
        $isAdmin = ($currentUser['role'] === 'admin');

        if (!$isCandidate && !$isEmployer && !$isAdmin) {
            http_response_code(403);
            die("403 Forbidden - Bạn không có quyền xem hoặc tải CV của hồ sơ này.");
        }

        $filePath = __DIR__ . '/../../' . $application['cv_path'];
        if (!file_exists($filePath)) {
            die("Tập tin CV không tồn tại trên máy chủ.");
        }

        $fileName = $application['cv_filename'] ?: basename($filePath);
        $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $contentType = 'application/octet-stream';
        if ($fileExt === 'pdf') {
            $contentType = 'application/pdf';
        } elseif ($fileExt === 'docx') {
            $contentType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        } elseif ($fileExt === 'doc') {
            $contentType = 'application/msword';
        }

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: inline; filename="' . addslashes($fileName) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}

