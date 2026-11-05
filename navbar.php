<div class="top-accent-bar"></div>
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand brand-logo" href="/">
            <img src="/assets/images/logo.svg" alt="InternHub Logo">
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link" href="/"><i class="bi bi-house-door me-1"></i>Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/jobs"><i class="bi bi-briefcase me-1"></i>Vị trí thực tập</a>
                </li>
                <?php if ($currentUser && $currentUser['role'] === 'student'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/applications/history"><i class="bi bi-clock-history me-1"></i>Lịch sử ứng tuyển</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile"><i class="bi bi-person-badge me-1"></i>Hồ sơ & CV</a>
                    </li>
                <?php elseif ($currentUser && $currentUser['role'] === 'employer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/employer/dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard NTD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employer/jobs"><i class="bi bi-file-earmark-text me-1"></i>Tin của tôi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employer/candidates"><i class="bi bi-people me-1"></i>Hồ sơ ứng viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employer/interviews"><i class="bi bi-calendar-event me-1"></i>Lịch phỏng vấn</a>
                    </li>
                <?php elseif ($currentUser && $currentUser['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard"><i class="bi bi-shield-lock me-1"></i>Dashboard Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/companies"><i class="bi bi-buildings me-1"></i>Doanh nghiệp</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users"><i class="bi bi-people me-1"></i>Tài khoản</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/jobs"><i class="bi bi-list-task me-1"></i>Tất cả tin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/skills"><i class="bi bi-tags me-1"></i>Ngành & Kỹ năng</a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <?php if ($currentUser): ?>
                    <div class="dropdown">
                        <button class="btn btn-light position-relative rounded-circle p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 42px; height: 42px;">
                            <i class="bi bi-bell fs-5 text-secondary"></i>
                            <?php if (!empty($unreadNotificationsCount)): ?>
                                <span class="notification-badge"><?= $unreadNotificationsCount ?></span>
                            <?php endif; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="width: 320px; max-height: 400px; overflow-y: auto;">
                            <li class="dropdown-header fw-bold text-dark d-flex justify-content-between align-items-center">
                                <span>Thông báo mới</span>
                                <span class="badge bg-primary-subtle text-primary"><?= count($recentNotifications) ?></span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (empty($recentNotifications)): ?>
                                <li class="text-center py-3 text-muted small">Không có thông báo mới</li>
                            <?php else: ?>
                                <?php foreach ($recentNotifications as $notif): ?>
                                    <li>
                                        <a class="dropdown-item p-2 rounded dropdown-item-notif <?= !$notif['is_read'] ? 'bg-light fw-semibold' : '' ?>" href="<?= htmlspecialchars($notif['link'] ?? '#') ?>" data-notif-id="<?= $notif['id'] ?>">
                                            <div class="small text-dark mb-1"><?= htmlspecialchars($notif['title']) ?></div>
                                            <div class="text-muted small text-truncate" style="font-size: 0.78rem;"><?= htmlspecialchars($notif['message']) ?></div>
                                            <div class="text-muted" style="font-size: 0.7rem;"><?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?></div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light d-flex align-items-center gap-2 rounded-pill px-3 py-1 border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="/<?= htmlspecialchars($currentUser['avatar'] ?? 'assets/images/avatar_student1.svg') ?>" alt="Avatar" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
                            <span class="fw-semibold text-dark small text-truncate" style="max-width: 120px;"><?= htmlspecialchars($currentUser['name']) ?></span>
                            <i class="bi bi-chevron-down small text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-1">
                            <li class="px-3 py-2 border-bottom mb-1">
                                <span class="d-block fw-bold text-dark small"><?= htmlspecialchars($currentUser['name']) ?></span>
                                <span class="d-block text-muted small"><?= htmlspecialchars($currentUser['email']) ?></span>
                                <span class="badge bg-primary-subtle text-primary mt-1 text-uppercase" style="font-size: 0.65rem;"><?= htmlspecialchars($currentUser['role']) ?></span>
                            </li>
                            <?php if ($currentUser['role'] === 'student'): ?>
                                <li><a class="dropdown-item rounded small py-2" href="/profile"><i class="bi bi-person me-2"></i>Hồ sơ sinh viên</a></li>
                                <li><a class="dropdown-item rounded small py-2" href="/applications/history"><i class="bi bi-card-checklist me-2"></i>Lịch sử ứng tuyển</a></li>
                            <?php elseif ($currentUser['role'] === 'employer'): ?>
                                <li><a class="dropdown-item rounded small py-2" href="/employer/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard Nhà tuyển dụng</a></li>
                                <li><a class="dropdown-item rounded small py-2" href="/employer/jobs/create"><i class="bi bi-plus-circle me-2"></i>Đăng tin thực tập</a></li>
                            <?php elseif ($currentUser['role'] === 'admin'): ?>
                                <li><a class="dropdown-item rounded small py-2" href="/admin/dashboard"><i class="bi bi-gear me-2"></i>Trang quản trị Admin</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded small py-2 text-danger" href="/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="/auth/login" class="btn btn-light fw-semibold text-dark border px-3 py-2 rounded-pill small">Đăng nhập</a>
                    <a href="/auth/register" class="btn btn-primary-custom px-3 py-2 rounded-pill small">Đăng ký tài khoản</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-3">
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
</div>

<main>
