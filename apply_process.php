<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'config/database.php';

// Tạm thời giả lập sinh viên có ID = 3 (Nguyễn Đức Trung) đã đăng nhập hệ thống
// (Sau này khi làm phần đăng nhập, ta sẽ lấy id từ session chính thức)
$student_id = 3; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;

    // 1. Kiểm tra xem tin tuyển dụng có tồn tại và còn hạn hay không
    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
    $stmt->execute([$job_id]);
    $job = $stmt->fetch();

    if (!$job) {
        die("Vị trí tuyển dụng không tồn tại!");
    }

    // Kiểm tra quy tắc nghiệp vụ: Không ứng tuyển khi tin đã đóng hoặc quá hạn
    if ($job['status'] === 'closed' || $job['deadline'] < date('Y-m-d')) {
        die("<script>alert('Tin tuyển dụng này đã đóng hoặc quá hạn nộp! Hệ thống từ chối.'); window.history.back();</script>");
    }

    // 2. Kiểm tra quy tắc nghiệp vụ: Mỗi ứng viên chỉ có một hồ sơ đang hiệu lực cho mỗi tin
    $check_exist = $pdo->prepare("SELECT id FROM applications WHERE job_id = ? AND student_id = ?");
    $check_exist->execute([$job_id, $student_id]);
    if ($check_exist->rowCount() > 0) {
        echo "<script>alert('Bạn đã nộp hồ sơ cho vị trí này rồi! Không được nộp lần hai.'); window.history.back();</script>";
        exit;
    }

    // 3. Xử lý upload file CV
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['cv_file']['tmp_name'];
        $file_name = $_FILES['cv_file']['name'];
        $file_size = $_FILES['cv_file']['size'];
        
        // Lấy phần mở rộng file (đuôi file)
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Cho phép các định dạng file phổ biến và an toàn
        $allowed_exts = ['pdf', 'doc', 'docx'];
        
        if (!in_array($file_ext, $allowed_exts)) {
            echo "<script>alert('Chỉ chấp nhận file CV định dạng PDF, DOC hoặc DOCX!'); window.history.back();</script>";
            exit;
        }

        // Đặt tên file mới độc đáo để tránh bị trùng lặp
        $new_file_name = 'cv_' . $student_id . '_' . $job_id . '_' . time() . '.' . $file_ext;
        $upload_path = 'uploads/' . $new_file_name;

        // Di chuyển file từ thư mục tạm vào thư mục uploads của dự án
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // 4. Lưu thông tin ứng tuyển vào cơ sở dữ liệu dùng PDO prepared statement
            $insert = $pdo->prepare("INSERT INTO applications (job_id, student_id, cv_file_path, status) VALUES (?, ?, ?, 'submitted')");
            $insert->execute([$job_id, $student_id, $upload_path]);

            echo "<script>alert('Nộp hồ sơ thành công!'); window.location.href='index.php';</script>";
            exit;
        } else {
            echo "<script>alert('Lỗi khi tải file lên hệ thống!'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Vui lòng chọn file CV hợp lệ!'); window.history.back();</script>";
        exit;
    }
}
?>