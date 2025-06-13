console.log('File JS đã được tải');
document.addEventListener('DOMContentLoaded', function() {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextBtn = document.getElementById('nextStepBtn');
    const prevBtn = document.getElementById('prevStepBtn');
    const step1Indicator = document.getElementById('step1Indicator');
    const step2Indicator = document.getElementById('step2Indicator');
    const step1Connector = document.getElementById('step1Connector');

    // Ẩn nút Quay lại ở bước đầu tiên
    prevBtn.classList.add('hidden');

    // Xử lý nút Tiếp theo
    nextBtn.addEventListener('click', function() {
        if (step1.classList.contains('active')) {
            // Kiểm tra form hợp lệ trước khi chuyển bước
            if (validateStep1()) {
                step1.classList.remove('active');
                step2.classList.add('active');
                updateStepIndicators(2);
                prevBtn.classList.remove('hidden');
                nextBtn.textContent = 'Đăng tin';
            }
        } else {
            // Xử lý submit form
            document.getElementById('jobForm').submit();
        }
    });

    // Xử lý nút Quay lại
    prevBtn.addEventListener('click', function() {
        step2.classList.remove('active');
        step1.classList.add('active');
        updateStepIndicators(1);
        prevBtn.classList.add('hidden');
        nextBtn.textContent = 'Tiếp theo';
    });

    // Cập nhật chỉ báo bước
    function updateStepIndicators(activeStep) {
        if (activeStep === 1) {
            step1Indicator.classList.add('active');
            step1Indicator.classList.remove('completed');
            step2Indicator.classList.remove('active');
            step1Connector.classList.remove('active');
        } else {
            step1Indicator.classList.remove('active');
            step1Indicator.classList.add('completed');
            step2Indicator.classList.add('active');
            step1Connector.classList.add('active');
        }
    }

    // Kiểm tra form bước 1
    function validateStep1() {
        const requiredFields = step1.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
                // Thêm thông báo lỗi
                let errorMsg = field.nextElementSibling;
                if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message';
                    errorMsg.textContent = 'Vui lòng điền trường này';
                    field.parentNode.insertBefore(errorMsg, field.nextSibling);
                }
                errorMsg.style.display = 'block';
            } else {
                field.classList.remove('border-red-500');
                // Ẩn thông báo lỗi nếu có
                const errorMsg = field.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('error-message')) {
                    errorMsg.style.display = 'none';
                }
            }
        });

        if (!isValid) {
            // Cuộn đến trường đầu tiên bị lỗi
            const firstError = step1.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        return true;
    }

    // Xử lý sự kiện input để xóa class lỗi khi người dùng nhập liệu
    step1.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', function() {
            if (field.value.trim()) {
                field.classList.remove('border-red-500');
                const errorMsg = field.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('error-message')) {
                    errorMsg.style.display = 'none';
                }
            }
        });
    });
});
// Thêm sự kiện khi tài liệu đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM đã tải xong, đang khởi tạo form...');
    handleFormSteps();
    console.log('Đã khởi tạo xong');
});

// Kiểm tra xem file JS đã được tải chưa
console.log('Kết thúc file JS');