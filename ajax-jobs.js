function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const liveSearchInput = document.getElementById('live-job-search');
    const jobResultsContainer = document.getElementById('job-results-container');
    const totalCountBadge = document.getElementById('total-jobs-count');

    if (liveSearchInput && jobResultsContainer) {
        const performSearch = debounce(function () {
            const query = liveSearchInput.value.trim();
            const categorySelect = document.getElementById('filter-category');
            const category = categorySelect ? categorySelect.value : '';
            const locationSelect = document.getElementById('filter-location');
            const locationVal = locationSelect ? locationSelect.value : '';

            let url = `/api/jobs/search?q=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}&location=${encodeURIComponent(locationVal)}`;

            fetch(url)
                .then(res => res.json())
                .then(res => {
                    if (res.success && res.data) {
                        renderJobs(res.data, jobResultsContainer);
                        if (totalCountBadge) {
                            totalCountBadge.textContent = res.meta.total;
                        }
                    }
                })
                .catch(err => console.error(err));
        }, 300);

        liveSearchInput.addEventListener('input', performSearch);
        const categoryFilter = document.getElementById('filter-category');
        if (categoryFilter) categoryFilter.addEventListener('change', performSearch);
    }
});

function renderJobs(jobs, container) {
    if (!jobs || jobs.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <img src="/assets/images/empty_state.svg" alt="Empty" style="width: 140px;" class="mb-3">
                <h5 class="fw-bold text-dark">Không tìm thấy vị trí thực tập phù hợp</h5>
                <p class="text-muted">Hãy thử thay đổi từ khóa hoặc bộ lọc ngành nghề khác.</p>
            </div>
        `;
        return;
    }

    let html = '';
    jobs.forEach(job => {
        let skillsHtml = '';
        if (job.skills && job.skills.length > 0) {
            job.skills.slice(0, 3).forEach(s => {
                skillsHtml += `<span class="skill-tag">${escapeHtml(s.name)}</span> `;
            });
        }

        const expiredBadge = job.is_expired == 1 ? '<span class="badge bg-secondary-subtle text-secondary border">Đã đóng</span>' : '<span class="badge bg-success-subtle text-success border">Đang mở</span>';

        html += `
            <div class="col-md-6 mb-3 d-flex align-items-stretch">
                <div class="job-card w-100">
                    <div class="job-card-header">
                        <div class="job-logo-box" style="width: 52px; height: 52px; min-width: 52px; max-width: 52px; max-height: 52px; border-radius: 12px; overflow: hidden; padding: 4px; background: #fff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <img src="/${escapeHtml(job.company_logo)}" alt="${escapeHtml(job.company_name)}" width="44" height="44" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                        </div>
                        <div class="overflow-hidden flex-grow-1 ps-2">
                            <h6 class="mb-0 fw-bold text-truncate text-dark" style="font-size: 0.92rem;">${escapeHtml(job.company_name)}</h6>
                            <span class="text-muted small" style="font-size: 0.78rem;"><i class="bi bi-geo-alt me-1 text-danger"></i>${escapeHtml(job.company_city)}</span>
                        </div>
                    </div>
                    <h5 class="job-title">
                        <a href="/jobs/detail?id=${job.id}">${escapeHtml(job.title)}</a>
                    </h5>
                    <div class="job-badges">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">${escapeHtml(job.category_name)}</span>
                        ${expiredBadge}
                    </div>
                    <div class="job-skills-row">
                        ${skillsHtml}
                    </div>
                    <div class="job-card-footer">
                        <div class="overflow-hidden pe-2">
                            <span class="text-muted small d-block" style="font-size: 0.72rem;">Phụ cấp thực tập</span>
                            <div class="job-salary-amount text-truncate">${escapeHtml(job.allowance)}</div>
                        </div>
                        <a href="/jobs/detail?id=${job.id}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill px-3 py-1 flex-shrink-0">Chi tiết</a>
                    </div>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}


