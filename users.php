<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h2 class="fw-bold text-dark mb-1">Quản Lý Tài Khoản Người Dùng</h2>
        <p class="text-muted mb-0">Quản lý sinh viên, nhà tuyển dụng và phân quyền tài khoản trên toàn hệ thống.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="btn-group">
            <a href="/admin/users" class="btn btn-sm <?= empty($currentRole) ? 'btn-primary' : 'btn-outline-secondary' ?>">Tất cả người dùng</a>
            <a href="/admin/users?role=student" class="btn btn-sm <?= ($currentRole === 'student') ? 'btn-primary' : 'btn-outline-secondary' ?>">Sinh viên</a>
            <a href="/admin/users?role=employer" class="btn btn-sm <?= ($currentRole === 'employer') ? 'btn-primary' : 'btn-outline-secondary' ?>">Nhà tuyển dụng</a>
            <a href="/admin/users?role=admin" class="btn btn-sm <?= ($currentRole === 'admin') ? 'btn-primary' : 'btn-outline-secondary' ?>">Quản trị viên</a>
        </div>
    </div>

    <div class="bg-white p-4 border rounded-3 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Email đăng nhập</th>
                        <th>Vai trò</th>
                        <th>Số điện thoại</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#<?= $u['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="/<?= htmlspecialchars($u['avatar'] ?? 'assets/images/avatar_student1.svg') ?>" alt="Avatar" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($u['name']) ?></span>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="badge bg-danger-subtle text-danger border">Admin</span>
                                <?php elseif ($u['role'] === 'employer'): ?>
                                    <span class="badge bg-primary-subtle text-primary border">Doanh nghiệp</span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border">Sinh viên</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($u['phone'] ?? 'Chưa cập nhật') ?></td>
                            <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                            <td>
                                <?php if ($u['status'] === 'active'): ?>
                                    <span class="badge bg-success-subtle text-success border">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border">Bị khóa</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($u['role'] !== 'admin'): ?>
                                    <form action="/admin/users/toggle" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= ($u['status'] === 'active') ? 'btn-outline-danger' : 'btn-outline-success' ?> rounded-pill px-3 py-1">
                                            <?= ($u['status'] === 'active') ? 'Khóa TK' : 'Mở khóa' ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted small">Cố định</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
