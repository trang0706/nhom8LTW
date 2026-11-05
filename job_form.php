<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h2 class="fw-bold text-dark mb-1"><?= $job ? 'Chỉnh Sửa Tin Tuyển Dụng' : 'Đăng Tin Thực Tập Mới' ?></h2>
        <p class="text-muted mb-0">Cung cấp đầy đủ thông tin mô tả và yêu cầu để thu hút các ứng viên tiềm năng nhất.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="bg-white p-4 p-md-5 border rounded-3 shadow-sm" style="max-width: 900px; margin: 0 auto;">
        <form action="<?= $job ? '/employer/jobs/update' : '/employer/jobs/store' ?>" method="POST">
            <?php if ($job): ?>
                <input type="hidden" name="id" value="<?= $job['id'] ?>">
            <?php endif; ?>

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">Tiêu đề vị trí tuyển dụng <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg fs-6" placeholder="Ví dụ: Thực tập sinh Lập trình Web Fullstack (PHP & ReactJS)" value="<?= htmlspecialchars($job['title'] ?? '') ?>" required>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark">Chuyên ngành đào tạo <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn chuyên ngành --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (($job['category_id'] ?? 0) == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark">Địa điểm làm việc <span class="text-danger">*</span></label>
                    <input type="text" name="location" class="form-control" placeholder="Hà Nội, TP.HCM hoặc địa chỉ cụ thể" value="<?= htmlspecialchars($job['location'] ?? 'Hà Nội') ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark">Hình thức làm việc</label>
                    <select name="work_type" class="form-select">
                        <option value="Full-time" <?= (($job['work_type'] ?? '') === 'Full-time') ? 'selected' : '' ?>>Full-time (Toàn thời gian)</option>
                        <option value="Part-time" <?= (($job['work_type'] ?? '') === 'Part-time') ? 'selected' : '' ?>>Part-time (Bán thời gian)</option>
                        <option value="Hybrid" <?= (($job['work_type'] ?? '') === 'Hybrid') ? 'selected' : '' ?>>Hybrid (Linh hoạt)</option>
                        <option value="Remote" <?= (($job['work_type'] ?? '') === 'Remote') ? 'selected' : '' ?>>Remote (Từ xa)</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-dark">Mức phụ cấp thực tập</label>
                    <input type="text" name="allowance" class="form-control" placeholder="Ví dụ: 6.000.000 - 8.000.000 VNĐ/tháng" value="<?= htmlspecialchars($job['allowance'] ?? '6.000.000 - 8.000.000 VNĐ/tháng') ?>">
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label small fw-bold text-dark">Số lượng</label>
                    <input type="number" name="quantity" min="1" class="form-control" value="<?= (int)($job['quantity'] ?? 5) ?>" required>
                </div>

                <div class="col-md-2 col-6">
                    <label class="form-label small fw-bold text-dark">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="open" <?= (($job['status'] ?? 'open') === 'open') ? 'selected' : '' ?>>Mở tuyển</option>
                        <option value="closed" <?= (($job['status'] ?? '') === 'closed') ? 'selected' : '' ?>>Đóng tin</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold text-dark">Hạn chót nộp hồ sơ <span class="text-danger">*</span></label>
                    <input type="date" name="deadline" class="form-control" value="<?= htmlspecialchars($job['deadline'] ?? date('Y-m-d', strtotime('+30 days'))) ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">Mô tả công việc chi tiết <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="5" required placeholder="- Tham gia phát triển hệ thống...&#10;- Phối hợp làm việc cùng nhóm..."><?= htmlspecialchars($job['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">Yêu cầu đối với sinh viên <span class="text-danger">*</span></label>
                <textarea name="requirements" class="form-control" rows="5" required placeholder="- Sinh viên năm 3, 4 chuyên ngành CNTT...&#10;- Nắm vững kiến thức nền tảng về OOP, cơ sở dữ liệu..."><?= htmlspecialchars($job['requirements'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">Quyền lợi và chế độ đãi ngộ <span class="text-danger">*</span></label>
                <textarea name="benefits" class="form-control" rows="4" required placeholder="- Hỗ trợ phụ cấp hàng tháng cạnh tranh...&#10;- Đóng dấu thực tập chuẩn trường...&#10;- Cơ hội trở thành nhân viên chính thức..."><?= htmlspecialchars($job['benefits'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark mb-2">Kỹ năng chuyên môn yêu cầu</label>
                <div class="p-3 bg-light rounded-3 d-flex flex-wrap gap-3">
                    <?php foreach ($skills as $s): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="skills[]" value="<?= $s['id'] ?>" id="skill_<?= $s['id'] ?>" <?= in_array($s['id'], $selectedSkills) ? 'checked' : '' ?>>
                            <label class="form-check-label small" for="skill_<?= $s['id'] ?>">
                                <?= htmlspecialchars($s['name']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="/employer/jobs" class="btn btn-light rounded-pill px-4">Quay lại danh sách</a>
                <button type="submit" class="btn btn-primary-custom rounded-pill px-5">
                    <i class="bi bi-check2-circle me-1"></i> <?= $job ? 'Cập nhật tin tuyển dụng' : 'Đăng tin thực tập' ?>
                </button>
            </div>
        </form>
    </div>
</div>

