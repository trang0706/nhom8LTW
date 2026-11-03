<?php

class BaseController {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function render(string $view, array $data = []): void {
        extract($data);
        $currentUser = $this->currentUser();
        $unreadNotificationsCount = 0;
        $recentNotifications = [];

        if ($currentUser) {
            require_once __DIR__ . '/../Models/Notification.php';
            $notifModel = new Notification();
            $unreadNotificationsCount = $notifModel->getUnreadCount($currentUser['id']);
            $recentNotifications = $notifModel->getByUser($currentUser['id'], 5);
        }

        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            die("Không tìm thấy view: " . htmlspecialchars($view));
        }

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/layouts/navbar.php';
        require_once $viewPath;
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    protected function currentUser(): ?array {
        return $_SESSION['user'] ?? null;
    }

    protected function isLoggedIn(): bool {
        return isset($_SESSION['user']);
    }

    protected function isRole(string $role): bool {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === $role;
    }

    protected function requireAuth(): void {
        if (!$this->isLoggedIn()) {
            $_SESSION['flash_error'] = 'Vui lòng đăng nhập để tiếp tục.';
            $this->redirect('/auth/login');
        }
    }

    protected function requireRole(string $role): void {
        $this->requireAuth();
        if ($_SESSION['user']['role'] !== $role) {
            http_response_code(403);
            die("403 Forbidden - Bạn không có quyền truy cập trang này.");
        }
    }

    protected function redirect(string $url): void {
        header("Location: " . $url);
        exit;
    }

    protected function jsonResponse(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}


