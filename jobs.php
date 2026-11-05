<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h2 class="fw-bold text-dark mb-1">Quản Lý Toàn Bộ Tin Tuyển Dụng</h2>
        <p class="text-muted mb-0">Kiểm duyệt, giám sát chất lượng nội dung và quản lý tất cả các vị trí thực tập trên hệ thống.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="bg-white p-4 border rounded-3 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>ID</th>
                        <th>Vị trí thực tập</th>
                        <th>Doanh nghiệp</th>
                        <th>Chuyên ngành</th>
                        <th>Hạn nộp</th>
                        <th>Hồ sơ nhận</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php foreach ($jobs as $j): ?>
                        <tr>
                            <td>#<?= $j['id'] ?></td>
                            <td>
                                <a href="/jobs/detail?id=<?= $j['id'] ?>" class="fw-bold text-dark text-decoration-none hover-primary"><?= htmlspecialchars($j['title']) ?></a>
                            </td>
                            <td><?= htmlspecialchars($j['company_name']) ?></td>
                            <td><?= htmlspecialchars($j['category_name']) ?></td>
                            <td>
                                <span class="<?= $j['is_expired'] ? 'text-danger fw-bold' : 'text-dark' ?>">
                                    <?= date('d/m/Y', strtotime($j['deadline'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border"><?= (int)$j['total_applications'] ?> hồ sơ</span>
                            </td>
                            <td>
                                <?php if ($j['is_expired'] || $j['status'] !== 'open'): ?>
                                    <span class="badge bg-secondary-subtle text-secondary border">Đã đóng</span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border">Đang mở</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="/jobs/detail?id=<?= $j['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 me-1">
                                    Xem tin
                                </a>
                                <form action="/admin/jobs/delete" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vị trí thực tập này?');">
                                    <input type="hidden" name="id" value="<?= $j['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

