USE internship_portal;

-- Truy vấn 1: Xem lịch sử các bài đăng mà sinh viên 'Nguyễn Văn An' đã ứng tuyển kèm tên công ty và trạng thái (JOIN + WHERE)
SELECT 
    s.full_name AS student_name,
    j.title AS job_title,
    c.name AS company_name,
    a.applied_at,
    a.status AS application_status
FROM applications a
JOIN students s ON a.student_id = s.id
JOIN jobs j ON a.job_id = j.id
JOIN companies c ON j.company_id = c.id
WHERE s.full_name = 'Nguyễn Văn An';

-- Truy vấn 2: Lấy danh sách thông tin sinh viên đã nộp đơn vào bài đăng tuyển dụng ID = 1 (JOIN + WHERE)
SELECT 
    s.id AS student_id,
    s.full_name,
    s.email,
    s.major,
    s.gpa,
    a.applied_at,
    a.status
FROM applications a
JOIN students s ON a.student_id = s.id
WHERE a.job_id = 1;

-- Truy vấn 3: Tìm các đơn ứng tuyển được duyệt ('ACCEPTED') của sinh viên có GPA >= 3.0 (JOIN + WHERE)
SELECT 
    s.full_name,
    s.major,
    s.gpa,
    j.title AS job_title,
    a.status
FROM applications a
JOIN students s ON a.student_id = s.id
JOIN jobs j ON a.job_id = j.id
WHERE a.status = 'ACCEPTED' AND s.gpa >= 3.0;