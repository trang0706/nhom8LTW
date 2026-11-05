<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h2 class="fw-bold text-dark mb-1">Quản Lý Ngành Nghề & Kỹ Năng</h2>
        <p class="text-muted mb-0">Quản lý danh mục chuyên ngành đào tạo và các thẻ kỹ năng công nghệ.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="bg-white p-4 border rounded-3 shadow-sm mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle me-1 text-primary"></i>Thêm Chuyên Ngành Mới</h5>
                <form action="/admin/skills" method="POST">
                    <input type="hidden" name="action" value="create_category">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Tên ngành nghề / lĩnh vực <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: Điện toán đám mây & DevOps" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Slug đường dẫn</label>
                            <input type="text" name="slug" class="form-control" placeholder="dien-toan-dam-may-devops">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Biểu tượng Icon (Bootstrap Icons)</label>
                            <input type="text" name="icon" class="form-control" value="bi-cloud-check">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Mô tả ngắn</label>
                        <input type="text" name="description" class="form-control" placeholder="AWS, Azure, Docker, Kubernetes...">
                    </div>
                    <button type="submit" class="btn btn-primary-custom btn-sm rounded-pill px-4">
                        <i class="bi bi-plus-lg me-1"></i> Lưu chuyên ngành
                    </button>
                </form>
            </div>

            <div class="bg-white p-4 border rounded-3 shadow-sm">
                <h5 class="fw-bold text-dark mb-3">Danh Sách Chuyên Ngành Hiện Có</h5>
                <ul class="list-group list-group-flush small">
                    <?php foreach ($categories as $cat): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <div>
                                <i class="bi <?= htmlspecialchars($cat['icon']) ?> me-2 text-primary fs-6"></i>
                                <span class="fw-bold text-dark"><?= htmlspecialchars($cat['name']) ?></span>
                                <span class="badge bg-light text-muted border ms-2"><?= (int)($cat['job_count'] ?? 0) ?> tin</span>
                            </div>
                            <form action="/admin/skills" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ngành nghề này?');">
                                <input type="hidden" name="action" value="delete_category">
                                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="bg-white p-4 border rounded-3 shadow-sm mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle me-1 text-success"></i>Thêm Kỹ Năng Mới</h5>
                <form action="/admin/skills" method="POST">
                    <input type="hidden" name="action" value="create_skill">
                    <div class="row g-2 mb-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-bold text-dark">Tên kỹ năng công nghệ <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Golang, GraphQL, Kubernetes" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-dark">Phân nhóm</label>
                            <select name="category" class="form-select">
                                <option value="Backend">Backend</option>
                                <option value="Frontend">Frontend</option>
                                <option value="Database">Database</option>
                                <option value="Mobile">Mobile</option>
                                <option value="DevOps">DevOps</option>
                                <option value="AI/Data">AI/Data</option>
                                <option value="Design">Design</option>
                                <option value="QA/QC">QA/QC</option>
                                <option value="Security">Security</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-custom btn-sm rounded-pill px-4">
                        <i class="bi bi-plus-lg me-1"></i> Lưu kỹ năng
                    </button>
                </form>
            </div>

            <div class="bg-white p-4 border rounded-3 shadow-sm">
                <h5 class="fw-bold text-dark mb-3">Danh Sách Kỹ Năng Hệ Thống (<?= count($skills) ?>)</h5>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($skills as $s): ?>
                        <div class="badge bg-light text-dark border p-2 d-flex align-items-center gap-2">
                            <span><?= htmlspecialchars($s['name']) ?> <small class="text-muted">(<?= htmlspecialchars($s['category']) ?>)</small></span>
                            <form action="/admin/skills" method="POST" class="d-inline" onsubmit="return confirm('Xóa kỹ năng này?');">
                                <input type="hidden" name="action" value="delete_skill">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <button type="submit" class="border-0 bg-transparent text-danger p-0 ms-1" style="font-size: 0.75rem;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
