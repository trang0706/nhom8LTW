<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'config/database.php';

// Giả định sinh viên đang đăng nhập có ID = 3
$student_id = 3;

// Lấy danh sách các hồ sơ mà sinh viên này đã nộp, kết hợp với bảng jobs và companies để hiển thị thông tin
$stmt = $pdo->prepare("
    SELECT applications.*, jobs.title AS job_title, jobs.field, companies.name AS company_name 
    FROM applications 
    JOIN jobs ON applications.job_id = jobs.id 
    JOIN companies ON jobs.company_id = companies.id 
    WHERE applications.student_id = ?
    ORDER BY applications.id DESC
");
$stmt->execute([$student_id]);
$applications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Ứng Tuyển</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">🚀 Cổng Thực Tập</a>
            <div>
                <a href="index.php" class="btn btn-outline-light btn-sm me-2">Trang chủ</a>
                <a href="my_applications.php" class="btn btn-light btn-sm">Lịch sử ứng tuyển của tôi</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">📋 Lịch Sử Hồ Sơ Đã Nộp</h2>

        <?php if (count($applications) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-white shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>STT</th>
                            <th>Vị trí ứng tuyển</th>
                            <th>Công ty</th>
                            <th>Lĩnh vực</th>
                            <th>File CV đã nộp</th>
                            <th>Trạng thái hồ sơ</th>
                            <th>Thời gian nộp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $stt = 1; foreach ($applications as $app): ?>
                            <tr>
                            <td><?= $stt++ ?></td>
                                <td><strong><?= htmlspecialchars($app['job_title']) ?></strong></td>
                                <td><?= htmlspecialchars($app['company_name']) ?></td>
                                <td><?= htmlspecialchars($app['field']) ?></td>
                                <td><a href="<?= htmlspecialchars($app['cv_file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Xem CV</a></td>
                                <td>
                                    <?php 
                                        // Hiển thị trạng thái bằng nhãn màu sắc bắt mắt
                                        $status = $app['status'];
                                        $badgeClass = 'bg-secondary';
                                        $statusText = 'Đã nộp';

                                        if ($status === 'submitted') { $badgeClass = 'bg-primary'; $statusText = 'Đã nộp (Chờ xét duyệt)'; }
                                        elseif ($status === 'reviewing') { $badgeClass = 'bg-warning text-dark'; $statusText = 'Đang xét duyệt'; }
                                        elseif ($status === 'interview') { $badgeClass = 'bg-info text-dark'; $statusText = 'Mời phỏng vấn'; }
                                        elseif ($status === 'accepted') { $badgeClass = 'bg-success'; $statusText = 'Đã trúng tuyển / Đạt'; }
                                        elseif ($status === 'rejected') { $badgeClass = 'bg-danger'; $statusText = 'Từ chối'; }
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                </td>
                                <td><?= htmlspecialchars($app['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Bạn chưa nộp hồ sơ vào vị trí thực tập nào cả! Hãy <a href="index.php" class="alert-link">quay lại trang chủ</a> để tìm việc và ứng tuyển nhé.
            </div>
        <?php endif; ?>
    </div>

</body>
</html>