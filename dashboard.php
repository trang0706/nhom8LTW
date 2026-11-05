<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="job-logo-box">
                    <img src="/<?= htmlspecialchars($company['logo']) ?>" alt="Logo">
                </div>
                <div>
                    <h2 class="fw-bold text-dark mb-0"><?= htmlspecialchars($company['name']) ?></h2>
                    <span class="text-muted small">Bảng Điều Khiển & Thống Kê Tuyển Dụng Doanh Nghiệp</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="/employer/jobs/create" class="btn btn-primary-custom rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Đăng tin thực tập mới
                </a>
                <a href="/employer/candidates" class="btn btn-secondary-custom rounded-pill">
                    <i class="bi bi-people me-1"></i> Quản lý hồ sơ
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-6">
            <div class="stat-card">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-briefcase"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($openJobsCount) ?></h3>
                    <span class="text-muted small">Vị trí đang mở</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="stat-card">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-file-earmark-person"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['total'] ?? 0) ?></h3>
                    <span class="text-muted small">Tổng số hồ sơ</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="stat-card">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['interview'] ?? 0) ?></h3>
                    <span class="text-muted small">Mời phỏng vấn</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($stats['accepted'] ?? 0) ?></h3>
                    <span class="text-muted small">Trúng tuyển thực tập</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bg-white p-4 border rounded-3 shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-task me-2 text-primary"></i>Tin Tuyển Dụng Thuộc Doanh Nghiệp</h5>
                    <a href="/employer/jobs" class="small text-primary fw-semibold">Quản lý tất cả</a>
                </div>

                <?php if (empty($companyJobs)): ?>
                    <div class="text-center py-4 text-muted small">
                        Doanh nghiệp của bạn chưa đăng tin tuyển dụng nào.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th>Vị trí</th>
                                    <th>Hạn nộp</th>
                                    <th>Hồ sơ</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php foreach (array_slice($companyJobs, 0, 5) as $j): ?>
                                    <tr>
                                        <td>
                                            <a href="/jobs/detail?id=<?= $j['id'] ?>" class="fw-bold text-dark text-decoration-none hover-primary"><?= htmlspecialchars($j['title']) ?></a>
                                            <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($j['category_name']) ?></div>
                                        </td>
                                        <td>
                                            <span class="<?= $j['is_expired'] ? 'text-danger fw-bold' : 'text-dark' ?>">
                                                <?= date('d/m/Y', strtotime($j['deadline'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/employer/candidates?job_id=<?= $j['id'] ?>" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none">
                                                <?= (int)$j['total_applications'] ?> ứng viên
                                            </a>
                                        </td>
                                        <td>
                                            <?php if ($j['is_expired'] || $j['status'] !== 'open'): ?>
                                                <span class="badge bg-secondary-subtle text-secondary border">Đã đóng</span>
                                            <?php else: ?>
                                                <span class="badge bg-success-subtle text-success border">Đang mở</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <a href="/employer/candidates?job_id=<?= $j['id'] ?>" class="btn btn-sm btn-light border py-1 px-2" title="Xem ứng viên">
                                                <i class="bi bi-people"></i>
                                            </a>
                                            <a href="/employer/jobs/edit?id=<?= $j['id'] ?>" class="btn btn-sm btn-light border py-1 px-2" title="Chỉnh sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bg-white p-4 border rounded-3 shadow-sm mb-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-3"><i class="bi bi-pie-chart me-2 text-primary"></i>Phân Bổ Trạng Thái Hồ Sơ</h5>
                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span><i class="bi bi-clock me-2 text-primary"></i>Đã nộp mới:</span>
                        <strong class="text-primary"><?= (int)($stats['submitted'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span><i class="bi bi-hourglass me-2 text-warning"></i>Đang xem xét:</span>
                        <strong class="text-warning"><?= (int)($stats['reviewing'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span><i class="bi bi-calendar-event me-2 text-info"></i>Lịch phỏng vấn:</span>
                        <strong class="text-info"><?= (int)($stats['interview'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span><i class="bi bi-check-circle me-2 text-success"></i>Đã trúng tuyển:</span>
                        <strong class="text-success"><?= (int)($stats['accepted'] ?? 0) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span><i class="bi bi-x-circle me-2 text-danger"></i>Từ chối hồ sơ:</span>
                        <strong class="text-danger"><?= (int)($stats['rejected'] ?? 0) ?></strong>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 border rounded-3 shadow-sm">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-lightning-charge me-2 text-warning"></i>Thao Tác Nhanh</h6>
                <div class="d-grid gap-2">
                    <a href="/employer/jobs/create" class="btn btn-outline-primary btn-sm text-start py-2">
                        <i class="bi bi-plus-lg me-2"></i>Đăng tin thực tập mới
                    </a>
                    <a href="/employer/candidates" class="btn btn-outline-secondary btn-sm text-start py-2">
                        <i class="bi bi-card-checklist me-2"></i>Sàng lọc hồ sơ ứng viên
                    </a>
                    <a href="/employer/candidates/export" class="btn btn-outline-success btn-sm text-start py-2">
                        <i class="bi bi-file-earmark-excel me-2"></i>Xuất danh sách ứng viên (CSV)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
