<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">Quản Lý Hồ Sơ Ứng Viên Thực Tập</h2>
                <p class="text-muted mb-0">Theo dõi, tải CV, sàng lọc và cập nhật tiến trình phỏng vấn của các ứng viên.</p>
            </div>
            <div>
                <a href="/employer/candidates/export<?= $selectedJobId ? '?job_id=' . $selectedJobId : '' ?>" class="btn btn-outline-success rounded-pill">
                    <i class="bi bi-file-earmark-excel me-1"></i> Xuất Danh Sách Ra CSV
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="bg-white p-4 border rounded-3 shadow-sm mb-4">
        <form action="/employer/candidates" method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-dark">Lọc theo tin tuyển dụng</label>
                <select name="job_id" class="form-select">
                    <option value="">-- Tất cả tin tuyển dụng của công ty --</option>
                    <?php foreach ($jobs as $j): ?>
                        <option value="<?= $j['id'] ?>" <?= ($selectedJobId == $j['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($j['title']) ?> (<?= (int)$j['total_applications'] ?> hồ sơ)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-dark">Lọc theo trạng thái hồ sơ</label>
                <select name="status" class="form-select">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="submitted" <?= ($selectedStatus === 'submitted') ? 'selected' : '' ?>>Đã nộp mới</option>
                    <option value="reviewing" <?= ($selectedStatus === 'reviewing') ? 'selected' : '' ?>>Đang xét duyệt</option>
                    <option value="interview" <?= ($selectedStatus === 'interview') ? 'selected' : '' ?>>Mời phỏng vấn</option>
                    <option value="accepted" <?= ($selectedStatus === 'accepted') ? 'selected' : '' ?>>Đạt / Trúng tuyển</option>
                    <option value="rejected" <?= ($selectedStatus === 'rejected') ? 'selected' : '' ?>>Từ chối</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary-custom w-100 py-2">
                    <i class="bi bi-funnel"></i> Lọc hồ sơ
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white p-4 border rounded-3 shadow-sm">
        <?php if (empty($applications)): ?>
            <div class="text-center py-5">
                <img src="/assets/images/empty_state.svg" alt="Empty" style="width: 140px;" class="mb-3">
                <h5 class="fw-bold text-dark">Chưa có hồ sơ ứng viên nào</h5>
                <p class="text-muted small">Chưa có sinh viên nào nộp hồ sơ theo tiêu chí tìm kiếm này.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Ứng viên</th>
                            <th>Thông tin học tập</th>
                            <th>Vị trí ứng tuyển</th>
                            <th>Ngày nộp</th>
                            <th>Trạng thái</th>
                            <th>Tập tin CV</th>
                            <th class="text-end">Cập nhật</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($app['candidate_name']) ?></div>
                                    <div class="text-muted" style="font-size: 0.78rem;">
                                        <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($app['candidate_email']) ?>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.78rem;">
                                        <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($app['candidate_phone'] ?? 'Chưa cập nhật') ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= htmlspecialchars($app['university'] ?? 'ĐH Bách Khoa Hà Nội') ?></div>
                                    <div class="text-muted" style="font-size: 0.78rem;"><?= htmlspecialchars($app['major'] ?? 'CNTT') ?></div>
                                    <span class="badge bg-success-subtle text-success border">GPA: <?= htmlspecialchars($app['gpa'] ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($app['job_title']) ?></span>
                                </td>
                                <td>
                                    <?= date('d/m/Y H:i', strtotime($app['created_at'])) ?>
                                </td>
                                <td>
                                    <?php if ($app['status'] === 'submitted'): ?>
                                        <span class="status-badge status-submitted"><i class="bi bi-clock me-1"></i>Đã nộp</span>
                                    <?php elseif ($app['status'] === 'reviewing'): ?>
                                        <span class="status-badge status-reviewing"><i class="bi bi-hourglass me-1"></i>Đang xét</span>
                                    <?php elseif ($app['status'] === 'interview'): ?>
                                        <span class="status-badge status-interview"><i class="bi bi-calendar-check me-1"></i>Phỏng vấn</span>
                                    <?php elseif ($app['status'] === 'accepted'): ?>
                                        <span class="status-badge status-accepted"><i class="bi bi-check-circle me-1"></i>Trúng tuyển</span>
                                    <?php elseif ($app['status'] === 'rejected'): ?>
                                        <span class="status-badge status-rejected"><i class="bi bi-x-circle me-1"></i>Từ chối</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/applications/download-cv?id=<?= $app['id'] ?>" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="bi bi-file-earmark-pdf me-1"></i>Xem CV
                                    </a>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary-custom rounded-pill px-3 py-1" data-bs-toggle="modal" data-bs-target="#statusModal_<?= $app['id'] ?>">
                                        <i class="bi bi-arrow-repeat me-1"></i>Xử lý
                                    </button>

                                    <div class="modal fade text-start" id="statusModal_<?= $app['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold text-dark">Xét Duyệt Hồ Sơ Ứng Viên</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="/employer/candidates/update-status" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="application_id" value="<?= $app['id'] ?>">

                                                        <div class="p-3 bg-light rounded-3 mb-3 small">
                                                            <div><strong>Ứng viên:</strong> <?= htmlspecialchars($app['candidate_name']) ?> (<?= htmlspecialchars($app['candidate_email']) ?>)</div>
                                                            <div><strong>Vị trí:</strong> <?= htmlspecialchars($app['job_title']) ?></div>
                                                            <?php if (!empty($app['cover_letter'])): ?>
                                                                <div class="mt-2 text-secondary">
                                                                    <strong>Thư giới thiệu:</strong> <em>"<?= htmlspecialchars($app['cover_letter']) ?>"</em>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold text-dark">Chuyển trạng thái hồ sơ <span class="text-danger">*</span></label>
                                                            <select name="status" class="form-select select-app-status" required>
                                                                <option value="submitted" <?= ($app['status'] === 'submitted') ? 'selected' : '' ?>>Đã nộp (Chờ xử lý)</option>
                                                                <option value="reviewing" <?= ($app['status'] === 'reviewing') ? 'selected' : '' ?>>Đang xét duyệt hồ sơ</option>
                                                                <option value="interview" <?= ($app['status'] === 'interview') ? 'selected' : '' ?>>Mời phỏng vấn</option>
                                                                <option value="accepted" <?= ($app['status'] === 'accepted') ? 'selected' : '' ?>>Đạt / Trúng tuyển thực tập</option>
                                                                <option value="rejected" <?= ($app['status'] === 'rejected') ? 'selected' : '' ?>>Từ chối hồ sơ</option>
                                                            </select>
                                                        </div>

                                                        <div class="interview-fields p-3 bg-light rounded-3 mb-3" style="display: <?= ($app['status'] === 'interview') ? 'block' : 'none' ?>;">
                                                            <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-calendar-event me-1"></i>Thông Tin Lên Lịch Phỏng Vấn</h6>
                                                            <div class="mb-2">
                                                                <label class="form-label small text-dark fw-bold">Ngày & giờ phỏng vấn</label>
                                                                <input type="datetime-local" name="interview_date" class="form-control form-control-sm" value="<?= !empty($app['interview_date']) ? date('Y-m-d\TH:i', strtotime($app['interview_date'])) : '' ?>">
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="form-label small text-dark fw-bold">Hình thức</label>
                                                                <select name="location_type" class="form-select form-select-sm">
                                                                    <option value="online" <?= (($app['interview_type'] ?? '') === 'online') ? 'selected' : '' ?>>Trực tuyến (Google Meet / Zoom / MS Teams)</option>
                                                                    <option value="offline" <?= (($app['interview_type'] ?? '') === 'offline') ? 'selected' : '' ?>>Trực tiếp tại văn phòng doanh nghiệp</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="form-label small text-dark fw-bold">Đường link meeting / Địa chỉ phòng phỏng vấn</label>
                                                                <input type="text" name="location_detail" class="form-control form-control-sm" placeholder="https://meet.google.com/... hoặc Phòng họp Tầng 5" value="<?= htmlspecialchars($app['interview_detail'] ?? '') ?>">
                                                            </div>
                                                            <div>
                                                                <label class="form-label small text-dark fw-bold">Ghi chú cho buổi phỏng vấn</label>
                                                                <input type="text" name="interview_note" class="form-control form-control-sm" placeholder="Mang theo laptop hoặc bài tập lớn demo">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold text-dark">Ghi chú & Phản hồi tới sinh viên</label>
                                                            <textarea name="employer_note" class="form-control" rows="3" placeholder="Nhận xét về CV, lý do từ chối hoặc thông tin bổ sung..."><?= htmlspecialchars($app['employer_note'] ?? '') ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-primary-custom rounded-pill px-4">
                                                            <i class="bi bi-check2 me-1"></i> Lưu trạng thái
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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

