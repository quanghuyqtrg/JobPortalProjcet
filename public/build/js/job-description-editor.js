// Cấu hình API endpoints
const API_ENDPOINTS = {
    generateDescription: 'https://n8n.wepro.io.vn/webhook/description',
    regenerateTag: 'https://n8n.wepro.io.vn/webhook/REGENERATE_ENDPOINT' // Will be replaced with actual endpoint
};

// Khởi tạo CKEditor nếu có
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra nếu có textarea cần CKEditor
    if (document.getElementById('description')) {
        if (typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(document.querySelector('#description'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
                })
                .then(editor => {
                    window.editor = editor;
                })
                .catch(error => {
                    console.error('Lỗi khi khởi tạo CKEditor:', error);
                });
        } else {
            console.error('CKEditor chưa được tải. Vui lòng kiểm tra lại CDN.');
        }
    }

    // Khởi tạo tooltip nếu có
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

// Hàm chính tạo mô tả công việc
async function generateJobDescription() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) loadingOverlay.style.display = 'flex';

    try {
        // Lấy dữ liệu từ form
        const title = document.getElementById('title')?.value;
        const location = document.getElementById('location')?.value;
        const experience = document.querySelector('input[name="experience"]:checked')?.value || '';
        const workLocation = document.querySelector('input[name="work_location"]:checked')?.value || '';
        const jobType = document.querySelector('input[name="type"]:checked')?.value || '';
        const minSalary = document.getElementById('minimum_salary')?.value || 0;
        const maxSalary = document.getElementById('max_salary')?.value || 0;

        // Validate dữ liệu bắt buộc
        if (!title) {
            throw new Error('Vui lòng nhập tiêu đề công việc');
        }

        console.log('Gửi yêu cầu tạo mô tả công việc...', {
            job_title: title,
            location,
            experience,
            work_loaciton: workLocation,
            type: jobType,
            minimum_salary: minSalary,
            max_salary: maxSalary
        });

        // Gửi request trực tiếp đến API n8n
        const response = await fetch(API_ENDPOINTS.generateDescription, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                job_title: title,
                location: location,
                experience: experience,
                work_location: workLocation,
                type: jobType,
                minimum_salary: minSalary,
                max_salary: maxSalary
            })
        });

        console.log('Phản hồi từ server:', response.status);
        const responseText = await response.text();
        console.log('Nội dung phản hồi:', responseText);

        let data;
        try {
            data = responseText ? JSON.parse(responseText) : {};
        } catch (e) {
            console.error('Không thể phân tích JSON từ phản hồi:', responseText);
            throw new Error('Dữ liệu trả về không hợp lệ từ máy chủ');
        }

        if (!response.ok) {
            const errorMessage = data.message || data.error || `Lỗi máy chủ (${response.status})`;
            throw new Error(errorMessage);
        }

        // Cập nhật giao diện với dữ liệu mới
        updateUIWithGeneratedData(data);

    } catch (error) {
        console.error('Chi tiết lỗi:', {
            name: error.name,
            message: error.message,
            stack: error.stack
        });
        
        // Hiển thị thông báo lỗi thân thiện
        const errorMessage = error.message || 'Đã xảy ra lỗi khi tạo mô tả công việc';
        alert('Lỗi: ' + errorMessage);
        
        // Nếu là lỗi CSRF token
        if (error.message.includes('419') || error.message.toLowerCase().includes('csrf')) {
            alert('Phiên làm việc đã hết hạn. Vui lòng làm mới trang và thử lại.');
            window.location.reload();
        }
    } finally {
        if (loadingOverlay) loadingOverlay.style.display = 'none';
    }
}

// Hàm cập nhật giao diện với dữ liệu được tạo
function updateUIWithGeneratedData(data) {
    // 1. Cập nhật mô tả công việc
    if (data.description) {
        if (window.editor) {
            window.editor.setData(data.description);
        } else if (document.getElementById('description')) {
            document.getElementById('description').value = data.description;
        }
    }

    // 2. Cập nhật mục tiêu
    if (data.objectives && data.objectives.length > 0) {
        updateList('objectives', data.objectives);
    }

    // 3. Cập nhật kỹ năng
    if (data.skills && data.skills.length > 0) {
        updateList('skills', data.skills);
    }

    // 4. Cập nhật yêu cầu công việc
    if (data.requirements && data.requirements.length > 0) {
        updateList('requirements', data.requirements);
    }

    // 5. Cập nhật quyền lợi
    if (data.benefits && data.benefits.length > 0) {
        updateList('benefits', data.benefits);
    }

    // Cuộn đến phần mô tả
    document.getElementById('description').scrollIntoView({ behavior: 'smooth' });
}

// Hàm cập nhật danh sách (dùng chung cho objectives, skills, requirements, benefits)
function updateList(type, items) {
    const container = document.getElementById(`${type}Container`);
    const input = document.getElementById(type);
    if (!container || !input) return;

    // Xóa nội dung cũ
    container.innerHTML = '';

    // Thêm từng mục mới
    items.forEach(item => {
        const itemId = `${type}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 mb-2';
        div.innerHTML = `
            <input type="text" id="${itemId}" 
                   class="w-full px-3 py-2 border rounded" 
                   value="${item.replace(/"/g, '&quot;')}">
            <button type="button" onclick="removeListItem(this, '${type}')" 
                    class="text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    });

    // Cập nhật input ẩn
    updateHiddenInput(type);
}

// Hàm xóa một mục khỏi danh sách
function removeListItem(button, type) {
    const container = button.closest('.flex.items-center');
    if (container) {
        container.remove();
        updateHiddenInput(type);
    }
}

// Hàm cập nhật input ẩn chứa dữ liệu dạng JSON
function updateHiddenInput(type) {
    const container = document.getElementById(`${type}Container`);
    const input = document.getElementById(type);
    if (!container || !input) return;

    const items = Array.from(container.querySelectorAll('input[type="text"]'))
        .map(input => input.value.trim())
        .filter(Boolean);

    input.value = JSON.stringify(items);
}

// Thêm sự kiện input cho các trường động
document.addEventListener('input', function(e) {
    const input = e.target;
    if (input.matches('#objectivesContainer input[type="text"]')) {
        updateHiddenInput('objectives');
    } else if (input.matches('#skillsContainer input[type="text"]')) {
        updateHiddenInput('skills');
    }
});

// Hàm tạo lại một thẻ cụ thể (requirements hoặc benefits)
async function regenerateTag(tagType) {
    const title = document.getElementById('title')?.value;
    const description = window.editor?.getData() || '';
    
    if (!title || !description) {
        alert('Vui lòng nhập tiêu đề và mô tả công việc trước');
        return;
    }

    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) loadingOverlay.style.display = 'flex';

    try {
        const endpoint = API_ENDPOINTS.regenerateTag.replace('REGENERATE_ENDPOINT', `${tagType}-regenerate`);
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                title: title,
                description: description
            })
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || data.error || 'Lỗi khi tạo lại thẻ');
        }

        // Cập nhật giao diện với dữ liệu mới
        if (data[tagType] && data[tagType].length > 0) {
            updateList(tagType, data[tagType]);
        }

    } catch (error) {
        console.error('Lỗi khi tạo lại thẻ:', error);
        alert('Lỗi: ' + (error.message || 'Đã xảy ra lỗi'));
    } finally {
        if (loadingOverlay) loadingOverlay.style.display = 'none';
    }
}

// Đăng ký sự kiện khi DOM đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    // Thêm sự kiện click cho nút tạo mô tả tự động
    const generateBtn = document.getElementById('generateDescriptionBtn');
    if (generateBtn) {
        generateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            generateJobDescription();
        });
    }

    // Thêm sự kiện click cho các nút tạo lại
    document.querySelectorAll('[data-regenerate]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const tagType = this.getAttribute('data-regenerate');
            if (tagType) {
                regenerateTag(tagType);
            }
        });
    });
});
