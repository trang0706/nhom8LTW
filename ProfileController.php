<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/StudentProfile.php';

class ProfileController extends BaseController {
    public function index(): void {
        $this->requireRole('student');
        $currentUser = $this->currentUser();

        $userModel = new User();
        $user = $userModel->findById($currentUser['id']);

        $profileModel = new StudentProfile();
        $profile = $profileModel->findByUserId($currentUser['id']);

        $this->render('profile/index', [
            'pageTitle' => 'Hồ Sơ Cá Nhân & CV Sinh Viên',
            'user' => $user,
            'profile' => $profile
        ]);
    }

    public function update(): void {
        $this->requireRole('student');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }

        $currentUser = $this->currentUser();
        $userId = $currentUser['id'];

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $university = trim($_POST['university'] ?? '');
        $major = trim($_POST['major'] ?? '');
        $studentCode = trim($_POST['student_code'] ?? '');
        $gpa = !empty($_POST['gpa']) ? (float)$_POST['gpa'] : null;
        $skills = trim($_POST['skills'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        $userModel = new User();
        $userModel->update($userId, [
            'name' => $name,
            'phone' => $phone
        ]);

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;

        $cvPath = null;
        if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['cv_file'];
            $allowedExtensions = ['pdf', 'doc', 'docx'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedExtensions) && $file['size'] <= 5 * 1024 * 1024) {
                $uploadDir = __DIR__ . '/../../storage/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $safeName = 'CV_Profile_' . $userId . '_' . time() . '.' . $fileExt;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $safeName)) {
                    $cvPath = 'storage/uploads/' . $safeName;
                }
            }
        }

        $profileModel = new StudentProfile();
        $profileData = [
            'university'   => $university,
            'major'        => $major,
            'student_code' => $studentCode,
            'gpa'          => $gpa,
            'bio'          => $bio,
            'skills'       => $skills
        ];
        if ($cvPath) {
            $profileData['cv_path'] = $cvPath;
        }

        $profileModel->save($userId, $profileData);

        $_SESSION['flash_success'] = 'Cập nhật hồ sơ cá nhân thành công!';
        $this->redirect('/profile');
    }
}


