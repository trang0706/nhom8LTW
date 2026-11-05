<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h2 class="fw-bold text-dark mb-1">Bảng Điều Khiển Quản Trị Hệ Thống</h2>
        <p class="text-muted mb-0">Giám sát toàn diện người dùng, doanh nghiệp liên kết và các vị trí thực tập trên hệ thống.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-6">
            <div class="stat-card">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($totalUsers) ?></h3>
                    <span class="text-muted small">Người dùng (<?= $studentCount ?> SV, <?= $employerCount ?> NTD)</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-buildings"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($totalCompanies) ?></h3>
                    <span class="text-muted small">Doanh nghiệp</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="stat-card">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-briefcase"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($totalJobs) ?></h3>
                    <span class="text-muted small">Tổng vị trí đăng tuyển</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="stat-card">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($appStats['total'] ?? 0) ?></h3>
                    <span class="text-muted small">Tổng lượt ứng tuyển</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="bg-white p-4 border rounded-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Tin Tuyển Dụng Mới Nhất Hệ Thống</h5>
                    <a href="/admin/jobs" class="small text-primary fw-semibold">Xem tất cả</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Doanh nghiệp</th>
                                <th>Hạn nộp</th>
                                <th>Ứng tuyển</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <?php foreach ($recentJobs as $rj): ?>
                                <tr>
                                    <td>
                                        <a href="/jobs/detail?id=<?= $rj['id'] ?>" class="fw-bold text-dark text-decoration-none"><?= htmlspecialchars($rj['title']) ?></a>
                                    </td>
                                    <td><?= htmlspecialchars($rj['company_name']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($rj['deadline'])) ?></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border"><?= (int)$rj['total_applications'] ?> hồ sơ</span>
                                    </td>
                                    <td>
                                        <?php if ($rj['is_expired'] || $rj['status'] !== 'open'): ?>
                                            <span class="badge bg-secondary-subtle text-secondary border">Đã đóng</span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success border">Đang mở</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bg-white p-4 border rounded-3 shadow-sm mb-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-3"><i class="bi bi-bar-chart me-2 text-primary"></i>Tình Trạng Xét Tuyển Toàn Sàn</h5>
                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span>Đã nộp mới:</span>
                        <strong class="text-primary"><?= (int)($appStats['submitted'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span>Đang xem xét:</span>
                        <strong class="text-warning"><?= (int)($appStats['reviewing'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span>Được mời phỏng vấn:</span>
                        <strong class="text-info"><?= (int)($appStats['interview'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span>Trúng tuyển thực tập:</span>
                        <strong class="text-success"><?= (int)($appStats['accepted'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span>Từ chối hồ sơ:</span>
                        <strong class="text-danger"><?= (int)($appStats['rejected'] ?? 0) ?></strong>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 border rounded-3 shadow-sm">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-gear me-2 text-primary"></i>Quản Trị Danh Mục</h6>
                <div class="d-grid gap-2">
                    <a href="/admin/companies" class="btn btn-outline-primary btn-sm text-start py-2">
                        <i class="bi bi-buildings me-2"></i>Quản lý Doanh nghiệp
                    </a>
                    <a href="/admin/users" class="btn btn-outline-secondary btn-sm text-start py-2">
                        <i class="bi bi-people me-2"></i>Quản lý Tài khoản người dùng
                    </a>
                    <a href="/admin/skills" class="btn btn-outline-success btn-sm text-start py-2">
                        <i class="bi bi-tags me-2"></i>Quản lý Ngành nghề & Kỹ năng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

