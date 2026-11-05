<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h2 class="fw-bold text-dark mb-1">Lịch Sử Ứng Tuyển & Tiến Trình Xét Duyệt</h2>
        <p class="text-muted mb-0">Theo dõi trạng thái xử lý hồ sơ, phản hồi và lịch phỏng vấn từ các nhà tuyển dụng.</p>
    </div>
</div>

<div class="container mb-5">
    <?php if (empty($applications)): ?>
        <div class="bg-white p-5 border rounded-4 text-center shadow-sm">
            <img src="/assets/images/empty_state.svg" alt="Empty" style="width: 150px;" class="mb-3">
            <h4 class="fw-bold text-dark mb-2">Bạn chưa ứng tuyển vị trí nào</h4>
            <p class="text-muted small mb-4">Hàng trăm vị trí thực tập hấp dẫn đang chờ đón bạn. Hãy khám phá và nộp CV ngay hôm nay!</p>
            <a href="/jobs" class="btn btn-primary-custom px-4 py-2 rounded-pill">
                <i class="bi bi-search me-1"></i> Khám phá vị trí thực tập
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($applications as $app): ?>
                <div class="col-12">
                    <div class="bg-white p-4 border rounded-4 shadow-sm hover-lift">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom pb-3 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="job-logo-box" style="width: 52px; height: 52px; min-width: 52px; max-width: 52px; max-height: 52px; border-radius: 12px; overflow: hidden; padding: 4px; background: #fff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <img src="/<?= htmlspecialchars($app['company_logo']) ?>" alt="<?= htmlspecialchars($app['company_name']) ?>" width="44" height="44" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">
                                        <a href="/jobs/detail?id=<?= $app['job_id'] ?>" class="text-dark hover-primary"><?= htmlspecialchars($app['job_title']) ?></a>
                                    </h5>
                                    <div class="text-muted small d-flex flex-wrap gap-3">
                                        <span><i class="bi bi-buildings me-1 text-primary"></i><?= htmlspecialchars($app['company_name']) ?></span>
                                        <span><i class="bi bi-geo-alt me-1 text-danger"></i><?= htmlspecialchars($app['job_location']) ?></span>
                                        <span><i class="bi bi-cash me-1 text-success"></i><?= htmlspecialchars($app['job_allowance']) ?></span>
                                        <span><i class="bi bi-clock me-1 text-warning"></i>Ngày nộp: <?= date('d/m/Y H:i', strtotime($app['created_at'])) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-md-end">
                                <div class="mb-2">
                                    <?php if ($app['status'] === 'submitted'): ?>
                                        <span class="status-badge status-submitted"><i class="bi bi-clock me-1"></i>Đã nộp hồ sơ</span>
                                    <?php elseif ($app['status'] === 'reviewing'): ?>
                                        <span class="status-badge status-reviewing"><i class="bi bi-hourglass-split me-1"></i>Đang xét duyệt</span>
                                    <?php elseif ($app['status'] === 'interview'): ?>
                                        <span class="status-badge status-interview"><i class="bi bi-calendar-check me-1"></i>Mời phỏng vấn</span>
                                    <?php elseif ($app['status'] === 'accepted'): ?>
                                        <span class="status-badge status-accepted"><i class="bi bi-check-circle me-1"></i>Trúng tuyển</span>
                                    <?php elseif ($app['status'] === 'rejected'): ?>
                                        <span class="status-badge status-rejected"><i class="bi bi-x-circle me-1"></i>Từ chối</span>
                                    <?php endif; ?>
                                </div>
                                <a href="/applications/download-cv?id=<?= $app['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="bi bi-file-earmark-arrow-down me-1"></i>Xem lại CV đã nộp
                                </a>
                            </div>
                        </div>

                        <?php if (!empty($app['employer_note'])): ?>
                            <div class="p-3 bg-light rounded-3 mb-3 small">
                                <strong class="text-dark"><i class="bi bi-chat-left-text me-1 text-primary"></i>Phản hồi từ Nhà tuyển dụng:</strong>
                                <div class="text-secondary mt-1"><?= htmlspecialchars($app['employer_note']) ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($app['status'] === 'interview' && !empty($app['interview_date'])): ?>
                            <div class="p-3 border border-primary-subtle bg-primary-subtle rounded-3">
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-calendar-event me-2"></i>Lịch Hẹn Phỏng Vấn Chính Thức</h6>
                                <div class="row g-2 small text-dark">
                                    <div class="col-md-4">
                                        <strong>Thời gian:</strong> <?= date('d/m/Y H:i', strtotime($app['interview_date'])) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Hình thức:</strong> <?= ($app['interview_type'] === 'online') ? 'Trực tuyến (Online Meeting)' : 'Trực tiếp tại văn phòng' ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Địa điểm / Đường link:</strong> <?= htmlspecialchars($app['interview_detail']) ?>
                                    </div>
                                    <?php if (!empty($app['interview_note'])): ?>
                                        <div class="col-12 mt-2">
                                            <strong>Ghi chú:</strong> <?= htmlspecialchars($app['interview_note']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

