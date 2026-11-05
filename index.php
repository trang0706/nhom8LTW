<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h2 class="fw-bold text-dark mb-1">Hồ Sơ Cá Nhân & CV Sinh Viên</h2>
        <p class="text-muted mb-0">Hoàn thiện thông tin học tập và kỹ năng để tạo ấn tượng tốt nhất với nhà tuyển dụng.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="bg-white p-4 border rounded-3 shadow-sm text-center mb-4">
                <img src="/<?= htmlspecialchars($user['avatar'] ?? 'assets/images/avatar_student1.svg') ?>" alt="Avatar" class="rounded-circle border p-1 mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                <p class="text-muted small mb-2"><?= htmlspecialchars($user['email']) ?></p>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-3">Sinh viên Khoa Toán - CNTT</span>

                <div class="p-3 bg-light rounded-3 text-start small d-flex flex-column gap-2">
                    <div><strong class="text-dark">Trường:</strong> <?= htmlspecialchars($profile['university'] ?? 'Chưa cập nhật') ?></div>
                    <div><strong class="text-dark">Chuyên ngành:</strong> <?= htmlspecialchars($profile['major'] ?? 'Chưa cập nhật') ?></div>
                    <div><strong class="text-dark">Điểm GPA:</strong> <span class="badge bg-success"><?= htmlspecialchars($profile['gpa'] ?? 'N/A') ?></span></div>
                </div>
            </div>

            <div class="bg-white p-4 border rounded-3 shadow-sm">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>CV Mặc Định Hiện Tại</h6>
                <?php if (!empty($profile['cv_path']) && file_exists(__DIR__ . '/../../' . $profile['cv_path'])): ?>
                    <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between mb-3">
                        <div class="overflow-hidden pe-2">
                            <span class="d-block fw-bold small text-truncate"><?= basename($profile['cv_path']) ?></span>
                            <span class="text-muted small">Đã lưu trữ an toàn</span>
                        </div>
                        <a href="/<?= htmlspecialchars($profile['cv_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning small mb-3 border-0">
                        Bạn chưa tải lên CV mặc định. Hãy tải file CV của bạn bên cạnh để tiện nộp hồ sơ.
                    </div>
                <?php endif; ?>
                <div class="small text-muted">
                    <i class="bi bi-info-circle me-1"></i>Hỗ trợ các định dạng PDF, DOC, DOCX. Tối đa 5MB.
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="bg-white p-4 border rounded-3 shadow-sm">
                <h5 class="fw-bold text-dark mb-4 border-bottom pb-3">Cập Nhật Thông Tin Học Tập & Kỹ Năng</h5>
                <form action="/profile/update" method="POST" enctype="multipart/form-data">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Địa chỉ Email</label>
                            <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="0901234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Mã số sinh viên (MSSV)</label>
                            <input type="text" name="student_code" class="form-control" value="<?= htmlspecialchars($profile['student_code'] ?? '') ?>" placeholder="20210001">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Trường Đại học</label>
                            <input type="text" name="university" class="form-control" value="<?= htmlspecialchars($profile['university'] ?? 'Đại học Bách Khoa Hà Nội') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-dark">Chuyên ngành</label>
                            <input type="text" name="major" class="form-control" value="<?= htmlspecialchars($profile['major'] ?? 'Công nghệ thông tin') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-dark">Điểm GPA (thang 4)</label>
                            <input type="number" step="0.01" min="0" max="4" name="gpa" class="form-control" value="<?= htmlspecialchars($profile['gpa'] ?? '') ?>" placeholder="3.50">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Kỹ năng chuyên môn (cách nhau bằng dấu phẩy)</label>
                        <input type="text" name="skills" class="form-control" value="<?= htmlspecialchars($profile['skills'] ?? '') ?>" placeholder="PHP, MySQL, JavaScript, VueJS, Docker, Git...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Giới thiệu bản thân & Mục tiêu nghề nghiệp</label>
                        <textarea name="bio" class="form-control" rows="4" placeholder="Nêu định hướng nghề nghiệp, kinh nghiệm làm đồ án môn học hoặc bài tập lớn..."><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Cập nhật File CV Mới</label>
                        <input type="file" name="cv_file" id="cv_file" class="form-control" accept=".pdf,.doc,.docx">
                        <div class="form-text small text-muted">Nếu không muốn thay đổi file CV hiện tại, bạn có thể để trống trường này.</div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary-custom px-4 py-2 rounded-pill">
                            <i class="bi bi-floppy me-1"></i> Lưu thông tin hồ sơ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
