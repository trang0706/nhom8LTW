<?php
require_once 'config/database.php';
$stmt = $pdo->query("
    SELECT jobs.*, companies.name AS company_name 
    FROM jobs 
    JOIN companies ON jobs.company_id = companies.id 
    WHERE jobs.status = 'open' 
    ORDER BY jobs.id DESC
");
$jobs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cổng Đăng Tuyển và Ứng Tuyển Thực Tập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">🚀 Cổng Thực Tập</a>
        </div>
    </nav>
    <div class="container">
        <h2 class="mb-4">Danh Sách Vị Trí Thực Tập Mới Nhất</h2>
        <div class="row">
            <?php if (count($jobs) > 0): foreach ($jobs as $job): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= htmlspecialchars($job['title']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">🏢 <?= htmlspecialchars($job['company_name']) ?></h6>
                            <p class="card-text mb-1"><strong>Lĩnh vực:</strong> <?= htmlspecialchars($job['field']) ?></p>
                            <p class="card-text mb-1"><strong>Địa điểm:</strong> <?= htmlspecialchars($job['location']) ?></p>
                            <p class="card-text text-danger mb-3"><strong>Hạn nộp:</strong> <?= htmlspecialchars($job['deadline']) ?></p>
                            <a href="job_detail.php?id=<?= $job['id'] ?>" class="btn btn-outline-primary btn-sm">Xem chi tiết & Nộp CV</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="col-12"><div class="alert alert-warning">Chưa có vị trí tuyển dụng nào!</div></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>