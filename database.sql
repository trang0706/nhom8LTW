SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS interviews;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS job_skills;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS skills;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS student_profiles;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'employer', 'admin') NOT NULL DEFAULT 'student',
    phone VARCHAR(20) NULL,
    avatar VARCHAR(255) NULL,
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    name VARCHAR(200) NOT NULL,
    logo VARCHAR(255) NULL,
    website VARCHAR(255) NULL,
    address VARCHAR(255) NULL,
    city VARCHAR(100) NOT NULL DEFAULT 'Hà Nội',
    industry VARCHAR(100) NOT NULL DEFAULT 'Công nghệ thông tin',
    scale VARCHAR(50) DEFAULT '50-150 nhân viên',
    description TEXT NULL,
    status ENUM('active', 'pending', 'locked') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE student_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    university VARCHAR(150) NULL DEFAULT 'Đại học Bách Khoa Hà Nội',
    major VARCHAR(150) NULL DEFAULT 'Công nghệ phần mềm',
    student_code VARCHAR(50) NULL,
    gpa DECIMAL(3,2) NULL DEFAULT 3.45,
    bio TEXT NULL,
    skills TEXT NULL,
    cv_path VARCHAR(255) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT 'bi-code-slash',
    description VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(100) DEFAULT 'Kỹ thuật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    location VARCHAR(150) NOT NULL,
    work_type ENUM('Full-time', 'Part-time', 'Remote', 'Hybrid') DEFAULT 'Full-time',
    allowance VARCHAR(100) DEFAULT 'Thỏa thuận',
    quantity INT DEFAULT 1,
    deadline DATE NOT NULL,
    description MEDIUMTEXT NOT NULL,
    requirements MEDIUMTEXT NOT NULL,
    benefits MEDIUMTEXT NOT NULL,
    status ENUM('open', 'closed', 'hidden') DEFAULT 'open',
    views INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_skills (
    job_id INT NOT NULL,
    skill_id INT NOT NULL,
    PRIMARY KEY (job_id, skill_id),
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    candidate_id INT NOT NULL,
    cover_letter TEXT NULL,
    cv_path VARCHAR(255) NOT NULL,
    cv_filename VARCHAR(255) NOT NULL,
    status ENUM('submitted', 'reviewing', 'interview', 'accepted', 'rejected') DEFAULT 'submitted',
    employer_note TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_active_application (job_id, candidate_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE interviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL UNIQUE,
    interview_date DATETIME NOT NULL,
    location_type ENUM('online', 'offline') DEFAULT 'online',
    location_detail VARCHAR(255) NOT NULL,
    note TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (id, name, email, password, role, phone, avatar) VALUES
(1, 'Hệ Thống Quản Trị', 'admin@gmail.com', '$2y$10$pQccDWtkCk3XUk5b/mFTA./CMGp2ASQUixfuvDOWcg3xPUIGKnHaC', 'admin', '0901234567', 'assets/images/avatar_admin.svg'),
(2, 'FPT Software HR', 'recruiter1@fpt.com', '$2y$10$9Wcbfe.64XEo7D25O94/5ORBZDFYoUkIxl9UKD9WYGIwO.7uaebce', 'employer', '0912345678', 'assets/images/company_fpt.svg'),
(3, 'VNG Corporation Recruitment', 'recruiter2@vng.com', '$2y$10$9Wcbfe.64XEo7D25O94/5ORBZDFYoUkIxl9UKD9WYGIwO.7uaebce', 'employer', '0923456789', 'assets/images/company_vng.svg'),
(4, 'Viettel Solutions HR', 'recruiter3@viettel.com', '$2y$10$9Wcbfe.64XEo7D25O94/5ORBZDFYoUkIxl9UKD9WYGIwO.7uaebce', 'employer', '0934567890', 'assets/images/company_viettel.svg'),
(5, 'Nguyễn Văn An', 'student@gmail.com', '$2y$10$1MJDvNc4FrOqG/Ii7qon2.6mAI341.EWLqithA7kbSa4kuZI255IS', 'student', '0945678901', 'assets/images/avatar_student1.svg'),
(6, 'Trần Thị Mai', 'candidate2@gmail.com', '$2y$10$1MJDvNc4FrOqG/Ii7qon2.6mAI341.EWLqithA7kbSa4kuZI255IS', 'student', '0956789012', 'assets/images/avatar_student2.svg'),
(7, 'VinAI Research HR', 'recruiter@vinai.io', '$2y$10$9Wcbfe.64XEo7D25O94/5ORBZDFYoUkIxl9UKD9WYGIwO.7uaebce', 'employer', '0967890123', 'assets/images/company_vinai.svg'),
(8, 'Tiki Tech Talent', 'recruiter@tiki.vn', '$2y$10$9Wcbfe.64XEo7D25O94/5ORBZDFYoUkIxl9UKD9WYGIwO.7uaebce', 'employer', '0978901234', 'assets/images/company_tiki.svg'),
(9, 'MISA Talent Acquisition', 'recruiter@misa.com.vn', '$2y$10$9Wcbfe.64XEo7D25O94/5ORBZDFYoUkIxl9UKD9WYGIwO.7uaebce', 'employer', '0989012345', 'assets/images/company_misa.svg');

INSERT INTO companies (id, user_id, name, logo, website, address, city, industry, scale, description, status) VALUES
(1, 2, 'FPT Software', 'assets/images/company_fpt.svg', 'https://fptsoftware.com', 'Khu CNC Hòa Lạc, Thạch Thất, Hà Nội', 'Hà Nội', 'Công nghệ thông tin', '10.000+ nhân viên', 'Tập đoàn công nghệ hàng đầu Việt Nam cung cấp giải pháp chuyển đổi số toàn cầu.', 'active'),
(2, 3, 'VNG Corporation', 'assets/images/company_vng.svg', 'https://vng.com.vn', 'VNG Campus, Quận 7, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 'Game & Internet', '3.500+ nhân viên', 'Kỳ lân công nghệ đầu tiên tại Việt Nam với hệ sinh thái Zalo, Zing, VNGGames.', 'active'),
(3, 4, 'Viettel Solutions', 'assets/images/company_viettel.svg', 'https://viettel.vn', 'Số 1 Trần Hữu Dực, Nam Từ Liêm, Hà Nội', 'Hà Nội', 'Viễn thông & Dịch vụ số', '5.000+ nhân viên', 'Tổng công ty Giải pháp Doanh nghiệp Viettel kiến tạo xã hội số.', 'active'),
(4, 7, 'VinAI Research', 'assets/images/company_vinai.svg', 'https://vinai.io', 'Tòa nhà Symphony, Chu Huy Mân, Long Biên, Hà Nội', 'Hà Nội', 'Trí tuệ Nhân tạo', '500+ nhân viên', 'Viện nghiên cứu Trí tuệ Nhân tạo hàng đầu khu vực trực thuộc Vingroup.', 'active'),
(5, 8, 'Tiki Corporation', 'assets/images/company_tiki.svg', 'https://tiki.vn', 'Tòa nhà Viettel Complex, Quận 10, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 'Thương mại Điện tử', '2.000+ nhân viên', 'Hệ sinh thái thương mại điện tử đáng tin cậy nhất Việt Nam.', 'active'),
(6, 9, 'MISA Joint Stock Company', 'assets/images/company_misa.svg', 'https://misa.vn', 'Tòa nhà Technosoft, Cầu Giấy, Hà Nội', 'Hà Nội', 'Phần mềm Tài chính & ERP', '3.000+ nhân viên', 'Đơn vị phát triển phần mềm kế toán, quản trị doanh nghiệp phổ biến nhất.', 'active');

INSERT INTO student_profiles (user_id, university, major, student_code, gpa, bio, skills, cv_path) VALUES
(5, 'Đại học Bách Khoa Hà Nội', 'Khoa học Máy tính', '20210001', 3.65, 'Sinh viên năm cuối đam mê phát triển web hiện đại, có kinh nghiệm làm việc với PHP, MySQL, JavaScript.', 'PHP, MySQL, JavaScript, Git, VueJS', 'storage/uploads/sample_cv.pdf'),
(6, 'Đại học Công nghệ - ĐHQGHN', 'Kỹ thuật Phần mềm', '20210002', 3.52, 'Yêu thích nghiên cứu dữ liệu, trí tuệ nhân tạo và Machine Learning, mong muốn học hỏi môi trường chuyên nghiệp.', 'Python, Machine Learning, SQL, PyTorch', 'storage/uploads/sample_cv.pdf');

INSERT INTO categories (id, name, slug, icon, description) VALUES
(1, 'Lập trình Web & Phần mềm', 'lap-trinh-web-phan-mem', 'bi-code-slash', 'PHP, Node.js, React, Vue, Java, Web Fullstack'),
(2, 'Trí tuệ Nhân tạo & Data', 'tri-tue-nhan-tao-data', 'bi-cpu', 'Data Science, Machine Learning, Big Data, Python'),
(3, 'Lập trình Di động', 'lap-trinh-di-dong', 'bi-phone', 'Flutter, React Native, Android Kotlin, iOS Swift'),
(4, 'Kiểm thử & QA/QC', 'kiem-thu-qa-qc', 'bi-shield-check', 'Manual Testing, Automation Testing, Selenium'),
(5, 'An toàn Thông tin', 'an-toan-thong-tin', 'bi-shield-lock', 'Network Security, Pentest, SOC, Cryptography'),
(6, 'Thiết kế UI/UX Product', 'thiet-ke-ui-ux-product', 'bi-palette', 'Figma, Adobe XD, Wireframing, UX Research');

INSERT INTO skills (id, name, category) VALUES
(1, 'PHP', 'Backend'),
(2, 'MySQL', 'Database'),
(3, 'JavaScript', 'Frontend'),
(4, 'ReactJS', 'Frontend'),
(5, 'VueJS', 'Frontend'),
(6, 'Python', 'Backend/AI'),
(7, 'Machine Learning', 'AI/Data'),
(8, 'Flutter', 'Mobile'),
(9, 'Java / Spring Boot', 'Backend'),
(10, 'Docker', 'DevOps'),
(11, 'Git', 'Tools'),
(12, 'UI/UX Design', 'Design'),
(13, 'Manual Testing', 'QA/QC'),
(14, 'Network Security', 'Security');

INSERT INTO jobs (id, company_id, category_id, title, slug, location, work_type, allowance, quantity, deadline, description, requirements, benefits, status, views) VALUES
(1, 1, 1, 'Thực tập sinh Lập trình Web Fullstack (PHP & VueJS)', 'thuc-tap-sinh-lap-trinh-web-fullstack-php-vuejs', 'Hà Nội', 'Full-time', '6.000.000 - 8.000.000 VNĐ/tháng', 5, '2026-12-31', 
'Tham gia phát triển các phân hệ dự án web quy mô lớn cho khách hàng quốc tế. Phối hợp cùng Tech Lead thiết kế cơ sở dữ liệu và triển khai API chất lượng cao.',
'- Nắm vững kiến thức nền tảng về lập trình web (HTML/CSS/JS, PHP, MySQL).\n- Có sản phẩm hoặc bài tập lớn về MVC.\n- Tinh thần chủ động, ham học hỏi và tư duy logic tốt.',
'- Hỗ trợ phụ cấp thực tập cạnh tranh từ 6 - 8 triệu/tháng.\n- Cơ hội được ký hợp đồng chính thức ngay sau khi kết thúc kỳ thực tập.\n- Tham gia đào tạo bài bản cùng các chuyên gia hàng đầu.', 'open', 142),

(2, 2, 2, 'Thực tập sinh AI & Machine Learning Research', 'thuc-tap-sinh-ai-machine-learning-research', 'TP. Hồ Chí Minh', 'Hybrid', '8.000.000 - 10.000.000 VNĐ/tháng', 3, '2026-11-30',
'Nghiên cứu và triển khai mô hình học sâu (Deep Learning), xử lý ngôn ngữ tự nhiên (NLP) phục vụ các sản phẩm trí tuệ nhân tạo của VNG.',
'- Có nền tảng toán học, giải thuật và xác suất thống kê vững chắc.\n- Sử dụng thành thạo Python và các thư viện PyTorch/TensorFlow, Pandas, Scikit-learn.\n- Đam mê thử nghiệm công nghệ mới.',
'- Trợ cấp thực tập lên đến 10 triệu đồng/tháng.\n- Miễn phí ăn trưa, cà phê, phòng gym cao cấp tại VNG Campus.\n- Hướng dẫn trực tiếp bởi các Senior AI Engineers.', 'open', 215),

(3, 3, 3, 'Thực tập sinh Lập trình Mobile (Flutter / Android)', 'thuc-tap-sinh-lap-trinh-mobile-flutter-android', 'Hà Nội', 'Full-time', '5.500.000 - 7.500.000 VNĐ/tháng', 4, '2026-12-15',
'Tham gia xây dựng các ứng dụng di động phục vụ chuyển đổi số doanh nghiệp, tối ưu giao diện và hiệu năng trải nghiệm người dùng.',
'- Biết lập trình hướng đối tượng Dart/Flutter hoặc Java/Kotlin.\n- Hiểu về RESTful API và cách tích hợp backend.\n- Ưu tiên ứng viên có dự án demo đã xuất bản trên GitHub.',
'- Phụ cấp thực tập hấp dẫn, review đánh giá định kỳ.\n- Cấp laptop/máy tính cấu hình cao phục vụ công việc.\n- Đóng dấu mộc xác nhận kỳ thực tập chuẩn khoa.', 'open', 98),

(4, 1, 4, 'Thực tập sinh Đảm bảo Chất lượng Phần mềm (QA/QC)', 'thuc-tap-sinh-dam-bao-chat-luong-phan-mem-qa-qc', 'Hà Nội', 'Full-time', '5.000.000 - 7.000.000 VNĐ/tháng', 3, '2026-12-20',
'Lập tài liệu test case, thực hiện manual testing và nghiên cứu triển khai kịch bản automation test trên hệ thống phần mềm tài chính.',
'- Sinh viên chuyên ngành CNTT hoặc ngành liên quan.\n- Cẩn thận, tỉ mỉ, có kỹ năng phân tích logic và làm việc nhóm.\n- Hiểu biết về quy trình phát triển phần mềm (SDLC, Agile/Scrum).',
'- Đào tạo từ cơ bản đến nâng cao về Automation Test.\n- Hỗ trợ phụ cấp hàng tháng và quà tặng các dịp lễ tết.\n- Môi trường làm việc quốc tế năng động.', 'open', 84),

(5, 2, 6, 'Thực tập sinh Thiết kế UI/UX Sản phẩm Công nghệ', 'thuc-tap-sinh-thiet-ke-ui-ux-san-pham-cong-nghe', 'TP. Hồ Chí Minh', 'Hybrid', '6.000.000 - 9.000.000 VNĐ/tháng', 2, '2026-12-25',
'Nghiên cứu hành vi người dùng, vẽ wireframe, prototype và thiết kế giao diện ứng dụng web/mobile trên Figma cho các sản phẩm hàng triệu người dùng.',
'- Sử dụng tốt công cụ Figma, Adobe Illustrator hoặc Photoshop.\n- Có portfolio thiết kế sản phẩm (dù là dự án cá nhân hoặc trường học).\n- Có mắt thẩm mỹ hiện đại, tư duy vị nhân sinh.',
'- Phụ cấp từ 6 - 9 triệu/tháng tùy theo năng lực.\n- Được trực tiếp tham gia sản phẩm thực tế có người dùng thật.\n- Mentor 1-1 tận tình suốt thời gian thực tập.', 'open', 110),

(6, 3, 5, 'Thực tập sinh An toàn Thông tin & Pentest (Closed)', 'thuc-tap-sinh-an-toan-thong-tin-pentest-closed', 'Hà Nội', 'Full-time', '7.000.000 VNĐ/tháng', 2, '2026-09-01',
'Tin tuyển dụng thử nghiệm đã quá hạn nộp hồ sơ để kiểm thử quy tắc nghiệp vụ hệ thống.',
'- Kiến thức về bảo mật web, mạng máy tính, lỗ hổng OWASP Top 10.',
'- Môi trường Viettel Cyber Security chuyên nghiệp.', 'closed', 45),

(7, 4, 2, 'Thực tập sinh Nghiên cứu Computer Vision & Deep Learning', 'thuc-tap-sinh-nghien-cuu-computer-vision-deep-learning', 'Hà Nội', 'Full-time', '9.000.000 - 12.000.000 VNĐ/tháng', 3, '2026-12-30',
'Tham gia nghiên cứu và tối ưu các mô hình nhận diện khuôn mặt, phát hiện vật thể và tự hành dưới sự hướng dẫn của các tiến sĩ hàng đầu tại VinAI.',
'- Nắm vững Python, PyTorch, OpenCV.\n- Đọc hiểu tốt các bài báo khoa học quốc tế (CVPR, ICCV, NeurIPS).\n- GPA giỏi/xuất sắc là lợi thế lớn.',
'- Phụ cấp thực tập cao nhất thị trường lên đến 12 triệu/tháng.\n- Cơ hội công bố bài báo khoa học quốc tế có tên đồng tác giả.\n- Làm việc tại văn phòng hạng A Symphony đẳng cấp.', 'open', 189),

(8, 5, 1, 'Thực tập sinh Kỹ sư Backend Golang & Hệ Thống Phân Tán', 'thuc-tap-sinh-ky-su-backend-golang-he-thong-phan-tan', 'TP. Hồ Chí Minh', 'Full-time', '7.000.000 - 9.000.000 VNĐ/tháng', 4, '2026-12-28',
'Phát triển các microservices xử lý hàng triệu giao dịch mỗi ngày cho nền tảng thương mại điện tử Tiki, tối ưu cơ sở dữ liệu và caching Redis/Kafka.',
'- Kiến thức vững về cấu trúc dữ liệu và giải thuật.\n- Biết Golang hoặc sẵn sàng chuyển đổi nhanh từ Java/C++.\n- Hiểu về RESTful API và kiến trúc Microservices.',
'- Trợ cấp 7 - 9 triệu/tháng kèm phụ cấp ăn trưa.\n- Văn phòng hiện đại, trẻ trung, teambuilding định kỳ.\n- Được đào tạo kỹ năng thiết kế hệ thống chịu tải cao.', 'open', 165),

(9, 6, 1, 'Thực tập sinh Lập trình .NET Core & Cloud ERP', 'thuc-tap-sinh-lap-trinh-net-core-cloud-erp', 'Hà Nội', 'Full-time', '6.000.000 - 8.500.000 VNĐ/tháng', 5, '2026-12-20',
'Tham gia xây dựng các phân hệ phần mềm kế toán, hóa đơn điện tử và nền tảng quản trị doanh nghiệp MISA phục vụ hơn 250.000 doanh nghiệp.',
'- Thành thạo ngôn ngữ C#/.NET Core hoặc Java.\n- Nắm vững kiến thức SQL Server hoặc MySQL.\n- Tinh thần trách nhiệm cao, cẩn thận và chủ động.',
'- Môi trường làm việc đạt Top 10 nơi làm việc tốt nhất Việt Nam.\n- Lộ trình thăng tiến rõ ràng, ký hợp đồng chính thức sau 3 tháng.\n- Thưởng dự án và các hoạt động văn hóa sôi nổi.', 'open', 130);

INSERT INTO job_skills (job_id, skill_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 5), (1, 11),
(2, 6), (2, 7), (2, 11),
(3, 8), (3, 11),
(4, 13), (4, 11),
(5, 12),
(6, 14),
(7, 6), (7, 7), (7, 10),
(8, 10), (8, 11), (8, 2),
(9, 9), (9, 2), (9, 11);

INSERT INTO applications (id, job_id, candidate_id, cover_letter, cv_path, cv_filename, status, employer_note, created_at) VALUES
(1, 1, 5, 'Kính gửi ban tuyển dụng FPT Software, em là Nguyễn Văn An, sinh viên năm cuối Bách Khoa. Em rất mong muốn được thử sức với vị trí thực tập sinh Web Fullstack.', 'storage/uploads/sample_cv.pdf', 'CV_NguyenVanAn_Web.pdf', 'reviewing', 'Hồ sơ GPA tốt, đã qua vòng sơ loại.', '2026-10-20 09:30:00'),
(2, 2, 6, 'Em chào anh chị HR VNG, em gửi CV ứng tuyển vị trí AI Intern với định hướng nghiên cứu xử lý ngôn ngữ tự nhiên.', 'storage/uploads/sample_cv.pdf', 'CV_TranThiMai_AI.pdf', 'interview', 'Hẹn phỏng vấn kỹ thuật trực tuyến.', '2026-10-21 14:15:00');

INSERT INTO interviews (application_id, interview_date, location_type, location_detail, note) VALUES
(2, '2026-11-05 10:00:00', 'online', 'Google Meet: https://meet.google.com/xyz-thuc-tap', 'Phỏng vấn kỹ thuật và giải thuật cùng Tech Lead bộ phận AI.');

INSERT INTO notifications (user_id, title, message, link, is_read) VALUES
(5, 'Hồ sơ đã được tiếp nhận', 'FPT Software đã chuyển trạng thái hồ sơ của bạn sang Đang xét duyệt.', '/applications/history', 1),
(6, 'Lịch phỏng vấn mới', 'VNG Corporation đã xếp lịch phỏng vấn vị trí AI & Machine Learning Research vào ngày 05/11/2026.', '/applications/history', 0);


