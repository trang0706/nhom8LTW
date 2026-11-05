<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h2 class="fw-bold text-dark mb-1">Lịch Phỏng Vấn Thực Tập Sinh</h2>
        <p class="text-muted mb-0">Theo dõi danh sách các buổi phỏng vấn trực tuyến và trực tiếp đã được thiết lập.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="bg-white p-4 border rounded-3 shadow-sm">
        <?php if (empty($interviews)): ?>
            <div class="text-center py-5">
                <img src="/assets/images/empty_state.svg" alt="Empty" style="width: 140px;" class="mb-3">
                <h5 class="fw-bold text-dark">Chưa có lịch phỏng vấn nào</h5>
                <p class="text-muted small mb-3">Khi bạn chuyển trạng thái hồ sơ của ứng viên sang "Mời phỏng vấn", lịch hẹn sẽ hiển thị tại đây.</p>
                <a href="/employer/candidates" class="btn btn-primary-custom rounded-pill px-4">
                    <i class="bi bi-people me-1"></i> Xem danh sách ứng viên
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Thời gian phỏng vấn</th>
                            <th>Ứng viên</th>
                            <th>Vị trí ứng tuyển</th>
                            <th>Hình thức</th>
                            <th>Chi tiết địa điểm / Đường link</th>
                            <th>Ghi chú</th>
                            <th class="text-end">Hồ sơ CV</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php foreach ($interviews as $iv): ?>
                            <tr>
                                <td>
                                    <strong class="text-primary d-block fs-6"><?= date('H:i', strtotime($iv['interview_date'])) ?></strong>
                                    <span class="text-muted"><?= date('d/m/Y', strtotime($iv['interview_date'])) ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($iv['candidate_name']) ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($iv['candidate_email']) ?></div>
                                    <div class="text-muted small"><?= htmlspecialchars($iv['candidate_phone'] ?? 'Chưa có SĐT') ?></div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($iv['job_title']) ?></span>
                                </td>
                                <td>
                                    <?php if ($iv['location_type'] === 'online'): ?>
                                        <span class="badge bg-info-subtle text-info border"><i class="bi bi-camera-video me-1"></i>Online</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border"><i class="bi bi-geo-alt me-1"></i>Trực tiếp</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($iv['location_type'] === 'online' && strpos($iv['location_detail'], 'http') === 0): ?>
                                        <a href="<?= htmlspecialchars($iv['location_detail']) ?>" target="_blank" class="text-primary text-decoration-none">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>Tham gia phòng họp
                                        </a>
                                    <?php else: ?>
                                        <span><?= htmlspecialchars($iv['location_detail']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted"><?= htmlspecialchars($iv['note'] ?? '-') ?></span>
                                </td>
                                <td class="text-end">
                                    <a href="/applications/download-cv?id=<?= $iv['application_id'] ?>" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="bi bi-file-earmark-pdf"></i> CV
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

