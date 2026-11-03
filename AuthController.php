<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Company.php';
require_once __DIR__ . '/../Models/StudentProfile.php';

class AuthController extends BaseController {
    public function login(): void {
        if ($this->isLoggedIn()) {
            $this->redirectBasedOnRole();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['flash_error'] = 'Vui lòng nhập đầy đủ Email và Mật khẩu.';
                $this->render('auth/login', ['pageTitle' => 'Đăng Nhập Hệ Thống', 'email' => $email]);
                return;
            }

            if (!$this->isStrongPassword($password)) {
                $_SESSION['flash_error'] = 'Mật khẩu phải đủ 8 kí tự (bao gồm chữ hoa, chữ thường, số và kí tự đặc biệt)';
                $this->render('auth/login', ['pageTitle' => 'Đăng Nhập Hệ Thống', 'email' => $email]);
                return;
            }

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION['flash_error'] = 'Email hoặc mật khẩu không chính xác.';
                $this->render('auth/login', ['pageTitle' => 'Đăng Nhập Hệ Thống', 'email' => $email]);
                return;
            }

            if ($user['status'] !== 'active') {
                $_SESSION['flash_error'] = 'Tài khoản của bạn đang bị khóa hoặc chưa được kích hoạt.';
                $this->render('auth/login', ['pageTitle' => 'Đăng Nhập Hệ Thống', 'email' => $email]);
                return;
            }

            $_SESSION['user'] = [
                'id'     => $user['id'],
                'name'   => $user['name'],
                'email'  => $user['email'],
                'role'   => $user['role'],
                'phone'  => $user['phone'],
                'avatar' => $user['avatar']
            ];

            $_SESSION['flash_success'] = 'Đăng nhập thành công! Chào mừng ' . htmlspecialchars($user['name']);
            $this->redirectBasedOnRole();
        }

        $this->render('auth/login', ['pageTitle' => 'Đăng Nhập Hệ Thống']);
    }

    public function register(): void {
        if ($this->isLoggedIn()) {
            $this->redirectBasedOnRole();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? 'student';
            $phone = trim($_POST['phone'] ?? '');
            $companyName = trim($_POST['company_name'] ?? '');

            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['flash_error'] = 'Vui lòng điền các trường thông tin bắt buộc.';
                $this->render('auth/register', ['pageTitle' => 'Đăng Ký Tài Khoản']);
                return;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['flash_error'] = 'Mật khẩu xác nhận không khớp.';
                $this->render('auth/register', ['pageTitle' => 'Đăng Ký Tài Khoản']);
                return;
            }

            if (!$this->isStrongPassword($password)) {
                $_SESSION['flash_error'] = 'Mật khẩu phải đủ 8 kí tự (bao gồm chữ hoa, chữ thường, số và kí tự đặc biệt)';
                $this->render('auth/register', ['pageTitle' => 'Đăng Ký Tài Khoản']);
                return;
            }

            $userModel = new User();
            $existing = $userModel->findByEmail($email);
            if ($existing) {
                $_SESSION['flash_error'] = 'Địa chỉ Email này đã được đăng ký trong hệ thống.';
                $this->render('auth/register', ['pageTitle' => 'Đăng Ký Tài Khoản']);
                return;
            }

            $avatar = ($role === 'employer') ? 'assets/images/avatar_fpt.svg' : 'assets/images/avatar_student1.svg';
            $userId = $userModel->create([
                'name'     => $name,
                'email'    => $email,
                'password' => $password,
                'role'     => $role,
                'phone'    => $phone,
                'avatar'   => $avatar,
                'status'   => 'active'
            ]);

            if ($role === 'employer') {
                $compModel = new Company();
                $compModel->create([
                    'user_id' => $userId,
                    'name'    => !empty($companyName) ? $companyName : $name . ' Enterprise',
                    'logo'    => 'assets/images/company_fpt.svg',
                    'city'    => 'Hà Nội'
                ]);
            } else {
                $profModel = new StudentProfile();
                $profModel->save($userId, [
                    'university' => 'Đại học Bách Khoa Hà Nội',
                    'major'      => 'Công nghệ thông tin'
                ]);
            }

            $_SESSION['user'] = [
                'id'     => $userId,
                'name'   => $name,
                'email'  => $email,
                'role'   => $role,
                'phone'  => $phone,
                'avatar' => $avatar
            ];

            $_SESSION['flash_success'] = 'Đăng ký tài khoản thành công!';
            $this->redirectBasedOnRole();
        }

        $this->render('auth/register', ['pageTitle' => 'Đăng Ký Tài Khoản']);
    }

    public function logout(): void {
        unset($_SESSION['user']);
        session_destroy();
        session_start();
        $_SESSION['flash_success'] = 'Bạn đã đăng xuất an toàn khỏi hệ thống.';
        $this->redirect('/auth/login');
    }

    private function redirectBasedOnRole(): void {
        $user = $this->currentUser();
        if (!$user) {
            $this->redirect('/');
        }

        if ($user['role'] === 'admin') {
            $this->redirect('/admin/dashboard');
        } elseif ($user['role'] === 'employer') {
            $this->redirect('/employer/dashboard');
        } else {
            $this->redirect('/jobs');
        }
    }

    private function isStrongPassword(string $password): bool {
        return mb_strlen($password) >= 8
            && preg_match('/[a-z]/', $password)
            && preg_match('/[A-Z]/', $password)
            && preg_match('/[0-9]/', $password)
            && preg_match('/[\W_]/', $password);
    }
}


