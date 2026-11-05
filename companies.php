<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h2 class="fw-bold text-dark mb-1">Quản Lý Doanh Nghiệp Liên Kết</h2>
        <p class="text-muted mb-0">Quản lý danh sách các công ty, duyệt tài khoản tuyển dụng và khóa doanh nghiệp vi phạm.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="bg-white p-4 border rounded-3 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>ID</th>
                        <th>Doanh nghiệp</th>
                        <th>Tài khoản quản lý</th>
                        <th>Địa bàn</th>
                        <th>Quy mô</th>
                        <th>Số lượng tin</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php foreach ($companies as $comp): ?>
                        <tr>
                            <td>#<?= $comp['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="job-logo-box" style="width: 40px; height: 40px; min-width: 40px; border-radius: 10px; padding: 4px;">
                                        <img src="/<?= htmlspecialchars($comp['logo']) ?>" alt="Logo">
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($comp['name']) ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($comp['industry']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($comp['user_email'] ?? 'Chưa liên kết') ?></td>
                            <td><?= htmlspecialchars($comp['city']) ?></td>
                            <td><?= htmlspecialchars($comp['scale']) ?></td>
                            <td>
                                <span class="badge bg-light text-primary border"><?= (int)$comp['total_jobs'] ?> vị trí</span>
                            </td>
                            <td>
                                <?php if ($comp['status'] === 'active'): ?>
                                    <span class="badge bg-success-subtle text-success border">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border">Bị khóa</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <form action="/admin/companies/toggle" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $comp['id'] ?>">
                                    <button type="submit" class="btn btn-sm <?= ($comp['status'] === 'active') ? 'btn-outline-danger' : 'btn-outline-success' ?> rounded-pill px-3 py-1">
                                        <?= ($comp['status'] === 'active') ? 'Khóa' : 'Kích hoạt' ?>
                                    </button>
                                </form>
                                <a href="/jobs?q=<?= urlencode($comp['name']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 ms-1">
                                    Xem tin
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

