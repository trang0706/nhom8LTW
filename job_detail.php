<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/database.php';

// Lấy id công việc từ URL truyền sang
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn thông tin chi tiết công việc và tên công ty
$stmt = $pdo->prepare("
    SELECT jobs.*, companies.name AS company_name, companies.description AS company_desc, companies.address AS company_address 
    FROM jobs 
    JOIN companies ON jobs.company_id = companies.id 
    WHERE jobs.id = ?
");
$stmt->execute([$job_id]);
$job = $stmt->fetch();

if (!$job) {
    die("<div class='container mt-5'><h3>Không tìm thấy vị trí tuyển dụng này!</h3><a href='index.php'>Quay lại trang chủ</a></div>");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><= htmlspecialchars($job['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">🚀 Cổng Thực Tập</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Quay lại danh sách</a>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm p-4 mb-4">
                    <h2 class="text-primary mb-3"><?= htmlspecialchars($job['title']) ?></h2>
                    <h5 class="text-muted mb-3">🏢 <?= htmlspecialchars($job['company_name']) ?></h5>
                    
                    <hr>
                    <p><strong>Lĩnh vực:</strong> <?= htmlspecialchars($job['field']) ?></p>
                    <p><strong>Địa điểm làm việc:</strong> <?= htmlspecialchars($job['location']) ?></p>
                    <p class="text-danger"><strong>Hạn nộp hồ sơ:</strong> <?= htmlspecialchars($job['deadline']) ?></p>
                    
                    <h4 class="mt-4">Mô tả công việc:</h4>
                    <p><nl2br><?= nl2br(htmlspecialchars($job['description'])) ?></nl2br></p>

                    <h4 class="mt-4">Yêu cầu ứng viên:</h4>
                    <p><nl2br><?= nl2br(htmlspecialchars($job['requirements'])) ?></nl2br></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm p-4">
                    <h4>Nộp Hồ Sơ Ứng Tuyển</h4>
                    <p class="text-muted small">Hãy nhanh tay ứng tuyển vị trí này trước hạn chót.</p>
                    
                    <!-- Form nộp hồ sơ (Sẽ xử lý ở bước sau) -->
                    <form action="apply_process.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="cv_file" class="form-label">Tải lên CV của bạn (PDF, DOCX):</label>
                            <input class="form-control" type="file" id="cv_file" name="cv_file" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Gửi Hồ Sơ Ứng Tuyển</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>