<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">Quản Lý Tin Tuyển Dụng Thực Tập</h2>
                <p class="text-muted mb-0">Theo dõi, chỉnh sửa và quản lý tình trạng đóng/mở của các vị trí tuyển dụng.</p>
            </div>
            <a href="/employer/jobs/create" class="btn btn-primary-custom rounded-pill">
                <i class="bi bi-plus-circle me-1"></i> Tạo tin tuyển dụng mới
            </a>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="bg-white p-4 border rounded-3 shadow-sm">
        <?php if (empty($jobs)): ?>
            <div class="text-center py-5">
                <img src="/assets/images/empty_state.svg" alt="Empty" style="width: 140px;" class="mb-3">
                <h5 class="fw-bold text-dark">Bạn chưa đăng tin tuyển dụng nào</h5>
                <p class="text-muted small mb-4">Hãy đăng tin ngay để tiếp cận hàng nghìn sinh viên công nghệ thông tin tiềm năng.</p>
                <a href="/employer/jobs/create" class="btn btn-primary-custom rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Đăng tin đầu tiên
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Vị trí tuyển dụng</th>
                            <th>Địa điểm</th>
                            <th>Hạn nộp</th>
                            <th>Phụ cấp</th>
                            <th>Số lượng hồ sơ</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td>
                                    <h6 class="mb-1 fw-bold">
                                        <a href="/jobs/detail?id=<?= $job['id'] ?>" class="text-dark hover-primary"><?= htmlspecialchars($job['title']) ?></a>
                                    </h6>
                                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($job['category_name']) ?></span>
                                    <span class="text-muted ms-2"><i class="bi bi-eye me-1"></i><?= (int)$job['views'] ?> views</span>
                                </td>
                                <td><?= htmlspecialchars($job['location']) ?></td>
                                <td>
                                    <span class="<?= $job['is_expired'] ? 'text-danger fw-bold' : 'text-dark' ?>">
                                        <?= date('d/m/Y', strtotime($job['deadline'])) ?>
                                    </span>
                                    <?php if ($job['is_expired']): ?>
                                        <span class="badge bg-danger-subtle text-danger d-block mt-1">Đã quá hạn</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong class="text-primary"><?= htmlspecialchars($job['allowance']) ?></strong></td>
                                <td>
                                    <a href="/employer/candidates?job_id=<?= $job['id'] ?>" class="badge bg-primary-subtle text-primary border border-primary-subtle p-2 text-decoration-none">
                                        <i class="bi bi-people me-1"></i><?= (int)$job['total_applications'] ?> ứng viên
                                    </a>
                                </td>
                                <td>
                                    <?php if ($job['is_expired'] || $job['status'] !== 'open'): ?>
                                        <span class="status-badge status-closed"><i class="bi bi-lock me-1"></i>Đã đóng</span>
                                    <?php else: ?>
                                        <span class="status-badge status-open"><i class="bi bi-check-circle me-1"></i>Đang mở</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary btn-toggle-job-status" data-job-id="<?= $job['id'] ?>" data-current-status="<?= $job['status'] ?>" title="Đóng / Mở tin">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                        <a href="/employer/candidates?job_id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-primary" title="Xem ứng viên">
                                            <i class="bi bi-people"></i>
                                        </a>
                                        <a href="/employer/candidates/export?job_id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-success" title="Xuất CSV">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <a href="/employer/jobs/edit?id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="/employer/jobs/delete" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin này không? Mọi hồ sơ liên quan sẽ bị xóa.');">
                                            <input type="hidden" name="id" value="<?= $job['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa tin">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

