document.addEventListener('DOMContentLoaded', function () {
    const cvInput = document.getElementById('cv_file');
    if (cvInput) {
        cvInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const validExtensions = ['pdf', 'doc', 'docx'];
                const ext = file.name.split('.').pop().toLowerCase();
                if (!validExtensions.includes(ext)) {
                    alert('Lỗi: Định dạng file không hợp lệ! Vui lòng chỉ chọn file PDF, DOC hoặc DOCX.');
                    this.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert('Lỗi: Dung lượng file vượt quá giới hạn 5MB!');
                    this.value = '';
                    return;
                }
            }
        });
    }

    const toggleButtons = document.querySelectorAll('.btn-toggle-job-status');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const jobId = this.getAttribute('data-job-id');
            const currentStatus = this.getAttribute('data-current-status');
            const confirmMsg = currentStatus === 'open' ? 'Bạn có chắc chắn muốn đóng tin tuyển dụng này?' : 'Bạn muốn mở lại tin tuyển dụng này?';

            if (confirm(confirmMsg)) {
                const formData = new FormData();
                formData.append('id', jobId);

                fetch('/employer/jobs/toggle', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Không thể kết nối đến máy chủ.');
                });
            }
        });
    });

    const statusSelects = document.querySelectorAll('.select-app-status');
    statusSelects.forEach(select => {
        select.addEventListener('change', function () {
            const form = this.closest('form');
            const val = this.value;
            const interviewFields = form.querySelector('.interview-fields');
            if (interviewFields) {
                if (val === 'interview') {
                    interviewFields.style.display = 'block';
                } else {
                    interviewFields.style.display = 'none';
                }
            }
        });
    });

    const notifItems = document.querySelectorAll('.dropdown-item-notif');
    notifItems.forEach(item => {
        item.addEventListener('click', function () {
            const notifId = this.getAttribute('data-notif-id');
            if (notifId) {
                const fd = new FormData();
                fd.append('id', notifId);
                fetch('/api/notifications/read', {
                    method: 'POST',
                    body: fd
                });
            }
        });
    });
});
