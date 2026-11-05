</main>

<footer class="footer-custom">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="/assets/images/logo.svg" alt="InternHub" style="height: 38px; filter: brightness(0) invert(1);">
                </div>
                <p class="small text-secondary mb-3">
                    Cổng thông tin kết nối doanh nghiệp và sinh viên thực tập Khoa Toán - CNTT. Môi trường ứng tuyển minh bạch, chuyên nghiệp và chuẩn mực.
                </p>
                <div class="d-flex gap-2">
                    <span class="badge bg-dark border text-light p-2"><i class="bi bi-shield-check me-1 text-success"></i>Bảo mật PDO</span>
                    <span class="badge bg-dark border text-light p-2"><i class="bi bi-cpu me-1 text-primary"></i>Chuẩn MVC PHP</span>
                    <span class="badge bg-dark border text-light p-2"><i class="bi bi-patch-check me-1 text-warning"></i>Khoa Toán - CNTT</span>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h5>Dành Cho Sinh Viên</h5>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="/jobs">Tìm vị trí thực tập</a></li>
                    <li><a href="/profile">Quản lý hồ sơ CV</a></li>
                    <li><a href="/applications/history">Theo dõi tiến độ ứng tuyển</a></li>
                    <li><a href="/jobs?category=1">Lập trình Web & Phần mềm</a></li>
                    <li><a href="/jobs?category=2">Trí tuệ nhân tạo & Data</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5>Dành Cho Doanh Nghiệp</h5>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="/employer/jobs/create">Đăng tin tuyển dụng thực tập</a></li>
                    <li><a href="/employer/candidates">Sàng lọc và quản lý ứng viên</a></li>
                    <li><a href="/employer/interviews">Lên lịch phỏng vấn sinh viên</a></li>
                    <li><a href="/employer/candidates">Xuất danh sách ứng viên CSV</a></li>
                    <li><a href="/employer/dashboard">Thống kê chiến dịch tuyển dụng</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5>Liên Hệ Ban Quản Trị</h5>
                <p class="small text-secondary mb-2"><i class="bi bi-geo-alt me-2 text-primary"></i>Văn phòng Khoa Toán - CNTT - Đại học Thủ Đô Hà Nội</p>
                <p class="small text-secondary mb-2"><i class="bi bi-envelope me-2 text-primary"></i>tuyendung.thuctap@it-faculty.edu.vn</p>
                <p class="small text-secondary mb-3"><i class="bi bi-telephone me-2 text-primary"></i>Hotline: 0866.468.126</p>
                <div class="small text-secondary">
                    Hỗ trợ sinh viên 24/7 trong suốt kỳ thực tập tốt nghiệp và thực tập doanh nghiệp.
                </div>
            </div>
        </div>

        <div class="pt-3 border-top border-secondary border-opacity-25 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-secondary">
            <div>&copy; <?= date('Y') ?> InternHub - Hệ Thống Cổng Tuyển Dụng & Ứng Tuyển Thực Tập. All Rights Reserved.</div>
            <div class="d-flex gap-3">
                <span>Phiên bản 2.8 MVC</span>
                <span>•</span>
                <span>PHP MySQL PDO</span>
                <span>•</span>
                <span class="text-success"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>Hệ thống trực tuyến</span>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/main.js?v=<?= file_exists(__DIR__ . '/../../../assets/js/main.js') ? filemtime(__DIR__ . '/../../../assets/js/main.js') : time() ?>"></script>
<script src="/assets/js/ajax-jobs.js?v=<?= file_exists(__DIR__ . '/../../../assets/js/ajax-jobs.js') ? filemtime(__DIR__ . '/../../../assets/js/ajax-jobs.js') : time() ?>"></script>
</body>
</html>

