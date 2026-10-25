USE internship_portal;

-- Dữ liệu mẫu Ngành nghề
INSERT INTO categories (name, description) VALUES 
('Công nghệ thông tin', 'Thực tập Lập trình, Data, QA/QC'),
('Marketing', 'Thực tập Digital Marketing, Content, SEO'),
('Tài chính - Kế toán', 'Thực tập Kế toán, Phân tích tài chính');

-- Dữ liệu mẫu Công ty
INSERT INTO companies (name, email, phone, website, address) VALUES 
('Công ty Cổ phần MISA', 'hr@misa.vn', '02437627888', 'https://misa.vn', 'Cầu Giấy, Hà Nội'),
('Tập đoàn FPT', 'recruitment@fpt.com.vn', '02473007300', 'https://fpt.com.vn', 'Nam Từ Liêm, Hà Nội');

-- Dữ liệu mẫu Bài tuyển dụng
INSERT INTO jobs (company_id, category_id, title, description, requirements, location, status) VALUES 
(1, 1, 'Thực tập sinh Web Developer (PHP/MySQL)', 'Tham gia phát triển hệ thống quản lý', 'Biết HTML, CSS, JavaScript, MySQL cơ bản', 'Hà Nội', 'OPEN'),
(2, 1, 'Thực tập sinh Python Data Analyst', 'Hỗ trợ xử lý dữ liệu và vẽ biểu đồ', 'Biết Python, SQL, tư duy logic tốt', 'Hà Nội', 'OPEN'),
(2, 2, 'Thực tập sinh Content Marketing', 'Viết bài truyền thông, quản lý Fanpage', 'Khả năng viết tốt, chủ động', 'Hà Nội', 'OPEN');

-- Dữ liệu mẫu Sinh viên
INSERT INTO students (full_name, email, phone, major, gpa, resume_url) VALUES 
('Nguyễn Văn An', 'an.nv@gmail.com', '0912345678', 'Khoa học Máy tính', 3.45, 'https://drive.google.com/cv_an.pdf'),
('Trần Thị Bình', 'binh.tt@gmail.com', '0987654321', 'Marketing', 3.20, 'https://drive.google.com/cv_binh.pdf');

-- Dữ liệu mẫu Đơn ứng tuyển
INSERT INTO applications (job_id, student_id, status, cover_letter) VALUES 
(1, 1, 'PENDING', 'Em mong muốn được rèn luyện kỹ năng lập trình web tại công ty.'),
(3, 2, 'ACCEPTED', 'Em có kinh nghiệm quản lý fanpage câu lạc bộ trường.')