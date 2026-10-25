<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'config/database.php';

// Giả định Nhà tuyển dụng đang đăng nhập có user_id = 2 (FPT Software)
$employer_user_id = 2;

// Lấy thông tin doanh nghiệp của nhà tuyển dụng này
$stmt_comp = $pdo->prepare("SELECT * FROM companies WHERE user_id = ?");
$stmt_comp->execute([$employer_user_id]);
$company = $stmt_comp->fetch();

if (!$company) {
    die("Tài khoản này chưa liên kết với doanh nghiệp nào!");
}

$company_id = $company['id'];

// Xử lý khi nhà tuyển dụng bấm cập nhật trạng thái hồ sơ ứng viên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $app_id = (int)$_POST['app_id'];
    $new_status = $_POST['status'];

    // QUY TẮC BẢO MẬT & PHÂN QUYỀN: 
    // Kiểm tra xem hồ sơ ứng tuyển này có thuộc về tin tuyển dụng của CHÍNH doanh nghiệp mình quản lý hay không
    // (Chống việc nhà tuyển dụng A sửa trái phép hồ sơ của doanh nghiệp B qua POST/URL)
    $check_perm = $pdo->prepare("
        SELECT applications.id 
        FROM applications 
        JOIN jobs ON applications.job_id = jobs.id 
        WHERE applications.id = ? AND jobs.company_id = ?
    ");
    $check_perm->execute([$app_id, $company_id]);
    
    if ($check_perm->rowCount() > 0) {
        // Hợp lệ -> Tiến hành cập nhật trạng thái
        $update = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $update.execute([$new_status, $app_id]);
        echo "<script>alert('Cập nhật trạng thái hồ sơ thành công!'); window.location.href='employer_dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Lỗi phân quyền: Bạn không có quyền thao tác trên hồ sơ này!'); window.history.back();</script>";
        exit;
    }
}

// Lấy danh sách tất cả hồ sơ ứng viên nộp vào các tin tuyển dụng của công ty này
$stmt_apps = $pdo->prepare("
    SELECT applications.*, jobs.title AS job_title, users.full_name AS student_name, users.email AS student_email, users.phone AS student_phone
    FROM applications 
    JOIN jobs ON applications.job_id = jobs.id 
    JOIN users ON applications.student_id = users.id
    WHERE jobs.company_id = ?
    ORDER BY applications.id DESC
");
$stmt_apps->execute([$company_id]);
$applications = $stmt_apps->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị Nhà Tuyển Dụng - <?= htmlspecialchars($company['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">🚀 Cổng Thực Tập (Quản Trị Doanh Nghiệp)</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Xem trang chủ</a>
        </div>
    </nav>

    <div class="container">
        <div class="alert alert-secondary mb-4">
            <h4>🏢 Doanh nghiệp: <?= htmlspecialchars($company['name']) ?></h4>
            <p class="mb-0">Quản lý các hồ sơ ứng viên đã nộp vào tin tuyển dụng của công ty bạn.</p>
        </div>

        <h3 class="mb-3">📥 Danh Sách Hồ Sơ Ứng Viên Đã Nộp</h3>

        <?php if (count($applications) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-white shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>STT</th>
                            <th>Ứng viên</th>
                            <th>Liên hệ</th>
                            <th>Vị trí ứng tuyển</th>
                            <th>CV</th>
                            <th>Trạng thái hiện tại</th>
                            <th>Thao tác xét duyệt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $stt = 1; foreach ($applications as $app): ?>
                            <tr>
                                <td><?= $stt++ ?></td>
                                <td><strong><?= htmlspecialchars($app['student_name']) ?></strong></td>
                                <td class="small">
                                    Email: <?= htmlspecialchars($app['student_email']) ?><br>
                                    SĐT: <?= htmlspecialchars($app['student_phone']) ?>
                                </td>
                                <td><?= htmlspecialchars($app['job_title']) ?></td>
                                <td><a href="<?= htmlspecialchars($app['cv_file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Xem CV</a></td>
                                <td>
                                    <?php 
                                        $status = $app['status'];
                                        $badgeClass = 'bg-secondary';
                                        $statusText = 'Đã nộp';

                                        if ($status === 'submitted') { $badgeClass = 'bg-primary'; $statusText = 'Đã nộp'; }
                                        elseif ($status === 'reviewing') { $badgeClass = 'bg-warning text-dark'; $statusText = 'Đang xét duyệt'; }
                                        elseif ($status === 'interview') { $badgeClass = 'bg-info text-dark'; $statusText = 'Phỏng vấn'; }
                                        elseif ($status === 'accepted') { $badgeClass = 'bg-success'; $statusText = 'Trúng tuyển / Đạt'; }
                                        elseif ($status === 'rejected') { $badgeClass = 'bg-danger'; $statusText = 'Từ chối'; }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <!-- Form cập nhật trạng thái hồ sơ -->
                                    <form action="employer_dashboard.php" method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="app_id" value="<?= $app['id'] ?>">
                                        <input type="hidden" name="update_status" value="1">
                                        
                                        <select name="status" class="form-select form-select-sm" style="width: 140px;">
                                            <option value="submitted" <?= $status === 'submitted' ? 'selected' : '' ?>>Đã nộp</option>
                                            <option value="reviewing" <?= $status === 'reviewing' ? 'selected' : '' ?>>Đang xét</option>
                                            <option value="interview" <?= $status === 'interview' ? 'selected' : '' ?>>Phỏng vấn</option>
                                            <option value="accepted" <?= $status === 'accepted' ? 'selected' : '' ?>>Đạt (Nhận)</option>
                                            <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Từ chối</option>
                                        </select>
                                        
                                        <button type="submit" class="btn btn-sm btn-success">Lưu</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                Chưa có ứng viên nào nộp hồ sơ vào các vị trí tuyển dụng của công ty bạn.
            </div>
        <?php endif; ?>
    </div>

</body>
</html>