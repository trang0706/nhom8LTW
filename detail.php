<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-4">
            <div class="d-flex align-items-start gap-3">
                <div class="job-detail-logo-box" style="width: 72px; height: 72px; min-width: 72px; max-width: 72px; max-height: 72px; border-radius: 16px; overflow: hidden; padding: 6px; background: #fff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <img src="/<?= htmlspecialchars($job['company_logo']) ?>" alt="<?= htmlspecialchars($job['company_name']) ?>" width="60" height="60" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?= htmlspecialchars($job['category_name']) ?></span>
                        <?php if ($job['is_expired'] || $job['status'] !== 'open'): ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle me-1"></i>Hết hạn / Đã đóng</span>
                        <?php else: ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i>Đang mở tuyển dụng</span>
                        <?php endif; ?>
                    </div>
                    <h2 class="fw-bold text-dark mb-2"><?= htmlspecialchars($job['title']) ?></h2>
                    <div class="text-muted small d-flex flex-wrap align-items-center gap-3">
                        <span><i class="bi bi-buildings me-1 text-primary"></i><?= htmlspecialchars($job['company_name']) ?></span>
                        <span><i class="bi bi-geo-alt me-1 text-danger"></i><?= htmlspecialchars($job['location']) ?></span>
                        <span><i class="bi bi-eye me-1 text-info"></i><?= number_format($job['views']) ?> lượt xem</span>
                        <span><i class="bi bi-calendar-event me-1 text-warning"></i>Hạn nộp: <strong class="<?= $job['is_expired'] ? 'text-danger' : 'text-dark' ?>"><?= date('d/m/Y', strtotime($job['deadline'])) ?></strong></span>
                    </div>
                </div>
            </div>

            <div class="flex-shrink-0 pt-md-2">
                <?php if ($job['is_expired'] || $job['status'] !== 'open'): ?>
                    <button class="btn btn-secondary px-4 py-2 rounded-pill fw-bold" disabled>
                        <i class="bi bi-lock me-1"></i> Vị trí đã đóng
                    </button>
                <?php elseif ($hasApplied): ?>
                    <div class="d-flex flex-column align-items-md-end">
                        <span class="badge bg-info-subtle text-info border border-info-subtle mb-1 p-2">
                            <i class="bi bi-check-all me-1"></i> Bạn đã nộp hồ sơ vị trí này
                        </span>
                        <a href="/applications/history" class="small text-primary fw-semibold">Xem trạng thái trong Lịch sử</a>
                    </div>
                <?php elseif ($currentUser && $currentUser['role'] === 'student'): ?>
                    <button class="btn btn-primary-custom btn-hover-shine px-4 py-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#applyModal">
                        <i class="bi bi-send-fill me-1"></i> Ứng tuyển ngay
                    </button>
                <?php elseif (!$currentUser): ?>
                    <a href="/auth/login" class="btn btn-primary-custom btn-hover-shine px-4 py-2 rounded-pill">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập để ứng tuyển
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary px-4 py-2 rounded-pill" disabled>
                        Tài khoản <?= htmlspecialchars($currentUser['role']) ?> không thể ứng tuyển
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bg-white p-4 p-md-5 border rounded-4 shadow-sm mb-4">
                <div class="row g-3 p-3 bg-light rounded-4 mb-4 text-center">
                    <div class="col-sm-4 border-end">
                        <span class="text-muted small d-block mb-1">Mức phụ cấp thực tập</span>
                        <strong class="text-primary fs-6"><?= htmlspecialchars($job['allowance']) ?></strong>
                    </div>
                    <div class="col-sm-4 border-end">
                        <span class="text-muted small d-block mb-1">Hình thức làm việc</span>
                        <strong class="text-dark fs-6"><?= htmlspecialchars($job['work_type']) ?></strong>
                    </div>
                    <div class="col-sm-4">
                        <span class="text-muted small d-block mb-1">Số lượng tuyển dụng</span>
                        <strong class="text-dark fs-6"><?= (int)$job['quantity'] ?> thực tập sinh</strong>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold text-dark border-start border-primary border-4 ps-2 mb-3">Mô tả công việc</h5>
                    <div class="text-secondary lh-lg" style="white-space: pre-line;">
                        <?= htmlspecialchars($job['description']) ?>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold text-dark border-start border-primary border-4 ps-2 mb-3">Yêu cầu ứng viên</h5>
                    <div class="text-secondary lh-lg" style="white-space: pre-line;">
                        <?= htmlspecialchars($job['requirements']) ?>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold text-dark border-start border-primary border-4 ps-2 mb-3">Quyền lợi được hưởng</h5>
                    <div class="text-secondary lh-lg" style="white-space: pre-line;">
                        <?= htmlspecialchars($job['benefits']) ?>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold text-dark border-start border-primary border-4 ps-2 mb-3">Kỹ năng yêu cầu</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (!empty($job['skills'])): ?>
                            <?php foreach ($job['skills'] as $skill): ?>
                                <span class="badge bg-light text-dark border px-3 py-2 fs-6 fw-normal">
                                    <i class="bi bi-tag me-1 text-primary"></i><?= htmlspecialchars($skill['name']) ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted small">Không yêu cầu kỹ năng cụ thể.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-4 border-top text-center">
                    <?php if ($job['is_expired'] || $job['status'] !== 'open'): ?>
                        <div class="alert alert-warning mb-0 border-0 rounded-3">
                            Vị trí thực tập này đã đóng tiếp nhận hồ sơ. Vui lòng tham khảo các vị trí tương tự bên dưới.
                        </div>
                    <?php elseif ($hasApplied): ?>
                        <div class="alert alert-info mb-0 border-0 rounded-3">
                            Bạn đã nộp hồ sơ ứng tuyển vào vị trí này. Trạng thái hiện tại: <strong><?= htmlspecialchars($existingApplication['status']) ?></strong>.
                        </div>
                    <?php elseif ($currentUser && $currentUser['role'] === 'student'): ?>
                        <button class="btn btn-primary-custom btn-hover-shine px-5 py-2 rounded-pill fs-6" data-bs-toggle="modal" data-bs-target="#applyModal">
                            <i class="bi bi-send-fill me-2"></i>Nộp Đơn Ứng Tuyển Ngay
                        </button>
                    <?php elseif (!$currentUser): ?>
                        <a href="/auth/login" class="btn btn-primary-custom btn-hover-shine px-5 py-2 rounded-pill fs-6">
                            Đăng nhập để nộp CV
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($relatedJobs)): ?>
                <div class="bg-white p-4 border rounded-4 shadow-sm">
                    <h5 class="fw-bold text-dark mb-3">Vị Trí Thực Tập Tương Tự</h5>
                    <div class="row g-3">
                        <?php foreach ($relatedJobs as $rJob): ?>
                            <div class="col-md-4">
                                <div class="p-3 border rounded-3 h-100 d-flex flex-column hover-lift bg-light">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="job-logo-box" style="width: 40px; height: 40px; min-width: 40px; border-radius: 10px; padding: 4px;">
                                            <img src="/<?= htmlspecialchars($rJob['company_logo']) ?>" alt="Logo">
                                        </div>
                                        <span class="text-muted small text-truncate fw-semibold"><?= htmlspecialchars($rJob['company_name']) ?></span>
                                    </div>
                                    <h6 class="fw-bold mb-2">
                                        <a href="/jobs/detail?id=<?= $rJob['id'] ?>" class="text-dark hover-primary text-truncate d-block" style="font-size: 0.95rem;"><?= htmlspecialchars($rJob['title']) ?></a>
                                    </h6>
                                    <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="text-primary small fw-bold"><?= htmlspecialchars($rJob['allowance']) ?></span>
                                        <a href="/jobs/detail?id=<?= $rJob['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill py-0 px-2 small">Xem</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="bg-white p-4 border rounded-4 shadow-sm mb-4">
                <div class="text-center mb-3">
                    <div class="company-sidebar-logo-wrapper" style="width: 76px; height: 76px; min-width: 76px; max-width: 76px; max-height: 76px; border-radius: 16px; overflow: hidden; padding: 6px; background: #fff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto;">
                        <img src="/<?= htmlspecialchars($job['company_logo']) ?>" alt="<?= htmlspecialchars($job['company_name']) ?>" width="64" height="64" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                    </div>
                    <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($job['company_name']) ?></h5>
                    <?php if (!empty($job['company_website'])): ?>
                        <a href="<?= htmlspecialchars($job['company_website']) ?>" target="_blank" class="small text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-up-right"></i>Trang web công ty
                        </a>
                    <?php endif; ?>
                </div>

                <hr class="my-3">

                <div class="small d-flex flex-column gap-2 mb-3">
                    <div><strong class="text-dark"><i class="bi bi-geo-alt me-1 text-primary"></i>Địa chỉ:</strong> <span class="text-muted"><?= htmlspecialchars($job['company_address'] ?? $job['location']) ?></span></div>
                    <div><strong class="text-dark"><i class="bi bi-people me-1 text-primary"></i>Quy mô:</strong> <span class="text-muted"><?= htmlspecialchars($job['company_scale'] ?? 'Đang cập nhật') ?></span></div>
                    <div><strong class="text-dark"><i class="bi bi-pin-map me-1 text-primary"></i>Khu vực:</strong> <span class="text-muted"><?= htmlspecialchars($job['company_city'] ?? $job['location']) ?></span></div>
                </div>

                <?php if (!empty($job['company_description'])): ?>
                    <div class="p-3 bg-light rounded-3 small text-secondary">
                        <?= htmlspecialchars($job['company_description']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white p-4 border rounded-4 shadow-sm">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-success me-2"></i>Quy Tắc Nghiệp Vụ &amp; Đảm Bảo</h6>
                <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-0">
                    <li><i class="bi bi-check2 text-primary me-2"></i>Mỗi sinh viên chỉ nộp 01 hồ sơ đang hiệu lực cho mỗi tin.</li>
                    <li><i class="bi bi-check2 text-primary me-2"></i>Hệ thống tự động khóa ứng tuyển khi quá hạn nộp.</li>
                    <li><i class="bi bi-check2 text-primary me-2"></i>File CV được lưu trữ bảo mật và kiểm tra định dạng an toàn.</li>
                    <li><i class="bi bi-check2 text-primary me-2"></i>Nhà tuyển dụng phản hồi và xếp lịch phỏng vấn trực tiếp.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if ($currentUser && $currentUser['role'] === 'student' && !$hasApplied && !$job['is_expired'] && $job['status'] === 'open'): ?>
<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="applyModalLabel">Nộp Hồ Sơ Ứng Tuyển</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/applications/apply" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="job_id" value="<?= $job['id'] ?>">

                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="small text-muted">Vị trí ứng tuyển:</div>
                        <div class="fw-bold text-dark"><?= htmlspecialchars($job['title']) ?></div>
                        <div class="small text-primary"><?= htmlspecialchars($job['company_name']) ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Thư giới thiệu / Lời nhắn tới nhà tuyển dụng</label>
                        <textarea name="cover_letter" class="form-control" rows="3" placeholder="Giới thiệu ngắn gọn lý do bạn phù hợp với vị trí thực tập này..."></textarea>
                    </div>

                    <?php if (!empty($studentProfile['cv_path']) && file_exists(__DIR__ . '/../../' . $studentProfile['cv_path'])): ?>
                        <div class="form-check p-3 bg-light rounded-3 mb-3">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="use_existing_cv" value="1" id="useExistingCv" checked>
                            <label class="form-check-label small fw-bold text-dark" for="useExistingCv">
                                Sử dụng CV mặc định trong hồ sơ cá nhân
                            </label>
                            <div class="small text-muted ms-4">
                                <i class="bi bi-file-earmark-pdf text-danger me-1"></i><?= basename($studentProfile['cv_path']) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3" id="uploadCvBlock">
                        <label class="form-label small fw-bold text-dark">Tải lên file CV mới <span class="text-danger">*</span></label>
                        <input type="file" name="cv_file" id="cv_file" class="form-control" accept=".pdf,.doc,.docx">
                        <div class="form-text small text-muted">Định dạng hỗ trợ: PDF, DOC, DOCX. Dung lượng tối đa: 5MB.</div>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="save_as_default_cv" value="1" id="saveAsDefault">
                        <label class="form-check-label small text-muted" for="saveAsDefault">
                            Lưu file này làm CV mặc định cho hồ sơ sinh viên của tôi
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary-custom rounded-pill px-4">
                        <i class="bi bi-send me-1"></i> Xác nhận nộp hồ sơ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
