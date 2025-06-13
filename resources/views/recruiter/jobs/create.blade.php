<x-app-layout>
    @push('scripts')
    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    <!-- Bootstrap JS (if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Job Description Editor -->
    <script src="{{ asset('js/job-description-editor.js') }}"></script>
    @endpush

@push('styles')
    <style>
        .tag-item {
            display: inline-block;
            background: #e9ecef;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            margin: 0.25rem;
            font-size: 0.875rem;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .step-section {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .step-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Style cho các bước và chuyển đổi */
        .step-indicator {
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background-color: #2563eb;
            color: white;
        }

        .step-indicator.completed {
            background-color: #10b981;
            color: white;
        }
        
        .step-connector {
            transition: all 0.3s ease;
        }
        
        .step-connector.active {
            background-color: #2563eb;
        }
        
        /* Style cho các bước form */
        .step-section {
            transition: opacity 0.3s ease, transform 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
            height: 0;
            overflow: hidden;
        }
        
        .step-section.active {
            opacity: 1;
            transform: translateY(0);
            height: auto;
            overflow: visible;
        }
        
        /* Style cho các trường lỗi */
        .border-red-500 {
            border-color: #ef4444 !important;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        }
    </style>
    @endpush
    
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Progress Steps -->
                <div class="px-8 pt-8 pb-4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Đăng tin tuyển dụng mới</h2>
                    <div class="flex items-center justify-center">
                        <div class="flex items-center">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center">
                                <div id="step1Indicator" class="step-indicator w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-lg font-semibold transition-colors duration-300">1</div>
                                <span class="text-sm font-medium text-gray-700 mt-2 transition-colors duration-300">Thông tin cơ bản</span>
                            </div>
                            
                            <!-- Connector -->
                            <div id="step1Connector" class="step-connector h-1 w-16 bg-gray-200 mx-2 transition-colors duration-300"></div>
                            
                            <!-- Step 2 -->
                            <div class="flex flex-col items-center">
                                <div id="step2Indicator" class="step-indicator w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-lg font-semibold transition-colors duration-300">2</div>
                                <span class="text-sm font-medium text-gray-500 mt-2 transition-colors duration-300">Chi tiết công việc</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Form Section -->
                <form action="{{ route('recruiter.jobs.store') }}" method="POST" class="p-8" id="jobForm">
                    @csrf
                    
                    <!-- Bước 1: Thông tin cơ bản -->
                    <div class="step-section active" id="step1">
                        <div class="space-y-6">
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Thông tin cơ bản</h3>
                                <p class="text-sm text-gray-500">Nhập các thông tin cơ bản về công việc của bạn</p>
                            </div>
                            
                            <!-- Job Code -->
                            <div class="space-y-1">
                                <label for="code" class="block text-sm font-medium text-gray-700">
                                    Mã công việc
                                </label>
                                <div class="relative">
                                    <input type="text" id="code" name="code"
                                        class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="VD: DEV-PHP-001"
                                        aria-describedby="codeHelp">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-hashtag text-gray-400" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <p id="codeHelp" class="mt-1 text-xs text-gray-500">Mã định danh cho công việc (nếu có)</p>
                            </div>

                            <!-- Job Title -->
                            <div class="space-y-1">
                                <label for="title" class="block text-sm font-medium text-gray-700">
                                    Tên công việc <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="title" name="title" required
                                        class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ví dụ: Lập trình viên PHP Senior"
                                        aria-required="true"
                                        aria-describedby="titleHelp">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <p id="titleHelp" class="mt-1 text-xs text-gray-500">Hãy đặt tiêu đề ngắn gọn và hấp dẫn</p>
                            </div>

                            <!-- Location -->
                            <div class="space-y-1">
                                <label for="location" class="block text-sm font-medium text-gray-700">
                                    Địa điểm làm việc
                                </label>
                                <div class="relative">
                                    <input type="text" id="location" name="location"
                                        class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ví dụ: Hà Nội, Hồ Chí Minh"
                                        aria-describedby="locationHelp">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <p id="locationHelp" class="mt-1 text-xs text-gray-500">Để trống nếu làm việc từ xa</p>
                            </div>

                            <!-- Salary Range -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label for="minimum_salary" class="block text-sm font-medium text-gray-700">
                                        Lương tối thiểu (triệu)
                                    </label>
                                    <div class="relative">
                                        <input type="number" id="minimum_salary" name="minimum_salary" min="0" step="0.5"
                                            class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="VD: 15">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-500">triệu</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label for="max_salary" class="block text-sm font-medium text-gray-700">
                                        Lương tối đa (triệu)
                                    </label>
                                    <div class="relative">
                                        <input type="number" id="max_salary" name="max_salary" min="0" step="0.5"
                                            class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="VD: 25">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-500">triệu</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Work Model & Job Type -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Work Model -->
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Mô hình làm việc <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <input type="radio" id="office" name="work_location" value="office" 
                                                   class="hidden peer" required>
                                            <label for="office" class="flex flex-col items-center justify-center p-3 w-full border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-building text-blue-600 text-lg mb-1" aria-hidden="true"></i>
                                                <span class="text-xs font-medium text-center">Văn phòng</span>
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" id="remote" name="work_location" value="remote" 
                                                   class="hidden peer">
                                            <label for="remote" class="flex flex-col items-center justify-center p-3 w-full border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-laptop-house text-blue-600 text-lg mb-1" aria-hidden="true"></i>
                                                <span class="text-xs font-medium text-center">Từ xa</span>
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" id="hybrid" name="work_location" value="hybrid" 
                                                   class="hidden peer">
                                            <label for="hybrid" class="flex flex-col items-center justify-center p-3 w-full border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-random text-blue-600 text-lg mb-1" aria-hidden="true"></i>
                                                <span class="text-xs font-medium text-center">Kết hợp</span>
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" id="flexible" name="work_location" value="flexible" 
                                                   class="hidden peer">
                                            <label for="flexible" class="flex flex-col items-center justify-center p-3 w-full border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-calendar-alt text-blue-600 text-lg mb-1" aria-hidden="true"></i>
                                                <span class="text-xs font-medium text-center">Linh hoạt</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Job Type -->
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Loại công việc <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <input type="radio" id="fulltime" name="type" value="fulltime" 
                                                   class="hidden peer" required>
                                            <label for="fulltime" class="flex flex-col items-center justify-center p-3 w-full border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-briefcase text-blue-600 text-lg mb-1" aria-hidden="true"></i>
                                                <span class="text-xs font-medium text-center">Toàn thời gian</span>
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" id="parttime" name="type" value="parttime" 
                                                   class="hidden peer">
                                            <label for="parttime" class="flex flex-col items-center justify-center p-3 w-full border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50 transition-colors">
                                                <i class="far fa-clock text-blue-600 text-lg mb-1" aria-hidden="true"></i>
                                                <span class="text-xs font-medium text-center">Bán thời gian</span>
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" id="contract" name="type" value="contract" 
                                                   class="hidden peer">
                                            <label for="contract" class="flex flex-col items-center justify-center p-3 w-full border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-file-signature text-blue-600 text-lg mb-1" aria-hidden="true"></i>
                                                <span class="text-xs font-medium text-center">Hợp đồng</span>
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" id="internship" name="type" value="internship" 
                                                   class="hidden peer">
                                            <label for="internship" class="flex flex-col items-center justify-center p-3 w-full border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-user-graduate text-blue-600 text-lg mb-1" aria-hidden="true"></i>
                                                <span class="text-xs font-medium text-center">Thực tập</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                    </div>
                                </div>
                            </div>
                            
                    <!-- Bước 2: Chi tiết công việc -->
                    <div class="step-section hidden" id="step2">
                        <div class="space-y-6">
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Chi tiết công việc</h3>
                                <p class="text-sm text-gray-500">Thêm mô tả chi tiết và yêu cầu cho công việc của bạn</p>
                            </div>

                            <!-- Job Content -->
                            <div class="space-y-1">
                                <label for="content" class="block text-sm font-medium text-gray-700">
                                    Mô tả công việc <span class="text-red-500">*</span>
                                </label>
                                <textarea id="content" name="content" rows="6" required
                                    class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Mô tả chi tiết về công việc, yêu cầu, quyền lợi..."></textarea>
                                <p class="mt-1 text-xs text-gray-500">Mô tả đầy đủ về công việc, yêu cầu và quyền lợi</p>
                            </div>

                            <!-- Job Objectives -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <label for="objectives" class="block text-sm font-medium text-gray-700">
                                        Mục tiêu công việc <span class="text-red-500">*</span>
                                    </label>
                                    <button type="button" onclick="addObjective()" class="text-xs text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-plus mr-1"></i> Thêm mục tiêu
                                    </button>
                                </div>
                                <div id="objectivesContainer" class="space-y-2">
                                    <!-- Objectives will be added here by JavaScript -->
                                </div>
                                <input type="hidden" name="objectives" id="objectives" value="[]">
                                <p class="mt-1 text-xs text-gray-500">Các mục tiêu chính của vị trí này</p>
                            </div>

                            <!-- Job Responsibilities -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <label for="responsibilities" class="block text-sm font-medium text-gray-700">
                                        Trách nhiệm công việc <span class="text-red-500">*</span>
                                    </label>
                                    <button type="button" onclick="addResponsibility()" class="text-xs text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-plus mr-1"></i> Thêm trách nhiệm
                                    </button>
                                </div>
                                <div id="responsibilitiesContainer" class="space-y-2">
                                    <!-- Responsibilities will be added here by JavaScript -->
                                </div>
                                <input type="hidden" name="responsibilities" id="responsibilities" value="[]">
                                <p class="mt-1 text-xs text-gray-500">Các nhiệm vụ chính của vị trí này</p>
                            </div>
                            
                            <!-- Job Description -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <div class="flex justify-between items-center">
                                        <label for="description" class="block text-sm font-medium text-gray-700">
                                            Mô tả công việc <span class="text-red-500">*</span>
                                        </label>
                                        <button type="button" id="generateDescriptionBtn" 
                                                class="text-sm text-blue-600 hover:text-blue-800 flex items-center transition-colors"
                                                aria-label="Tạo mô tả tự động">
                                            <i class="fas fa-magic mr-1.5" aria-hidden="true"></i> Tạo tự động
                                        </button>
                                    </div>
                                </div>
                                <div class="relative">
                                    <textarea name="description" id="description" rows="6" required
                                        class="block w-full px-4 py-3 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"

                                    <!-- Grid 2 cột cho các trường -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <!-- Mục tiêu công việc -->
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <label class="text-sm font-medium text-gray-700">Mục tiêu</label>
                                                <button type="button" id="regenerateObjectivesBtn" class="text-xs text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-sync-alt mr-1"></i>
                                                </button>
                                            </div>
                                            <div id="objectivesContainer" class="space-y-1"></div>
                                            <input type="hidden" name="objectives" id="objectivesInput">
                                        </div>

                                        <!-- Kỹ năng yêu cầu -->
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <label class="text-sm font-medium text-gray-700">Kỹ năng</label>
                                                <button type="button" id="regenerateSkillsBtn" class="text-xs text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-sync-alt mr-1"></i>
                                                </button>
                                            </div>
                                            <div id="skillsContainer" class="flex flex-wrap gap-1"></div>
                                            <input type="hidden" name="skills" id="skillsInput">
                                        </div>

                                        <!-- Yêu cầu công việc -->
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <label class="text-sm font-medium text-gray-700">Yêu cầu</label>
                                                <button type="button" id="regenerateRequirementsBtn" class="text-xs text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-sync-alt mr-1"></i>
                                                </button>
                                            </div>
                                            <div id="requirementsContainer" class="space-y-1"></div>
                                        </div>

                                        <!-- Quyền lợi -->
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <label class="text-sm font-medium text-gray-700">Quyền lợi</label>
                                                <button type="button" id="regenerateBenefitsBtn" class="text-xs text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-sync-alt mr-1"></i>
                                                </button>
                                            </div>
                                            <div id="benefitsContainer" class="space-y-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin bổ sung -->
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Thông tin bổ sung</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Tóm tắt</label>
                                        <textarea id="content" name="content" rows="2" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Tóm tắt ngắn gọn (tối đa 200 ký tự)" maxlength="200"></textarea>
                                    </div>
                                    <div>
                                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Mã công việc</label>
                                        <input type="text" id="code" name="code" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="VD: DEV-001">
                                    </div>
                                </div>
                            </div>

                            <!-- Nút điều hướng -->
                            <div class="flex flex-col sm:flex-row justify-between pt-4 border-t border-gray-200 space-y-3 sm:space-y-0">
                                <button type="button" id="prevStepBtn" class="px-4 py-2 text-sm border border-gray-300 text-gray-700 font-medium bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center justify-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                                </button>
                                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                    <button type="button" class="px-4 py-2 text-sm border border-gray-300 text-gray-700 font-medium bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Lưu nháp
                                    </button>
                                    <button type="submit" id="submitBtn" class="px-4 py-2 text-sm bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center">
                                        Đăng tin <i class="fas fa-paper-plane ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                            <!-- Navigation Buttons -->
                            <div class="flex justify-between pt-6 mt-8 border-t border-gray-200">
                                <button type="button" id="prevStepBtn" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 flex items-center hidden">
                                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                                </button>
                                <button type="button" id="nextStepBtn" class="ml-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 flex items-center">
                                    Tiếp theo <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Loading Overlay -->
                    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full mx-4">
                            <div class="flex flex-col items-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
                                <p class="text-gray-700">Đang tạo nội dung, vui lòng chờ...</p>
                                <p class="text-sm text-gray-500 mt-2">Quá trình có thể mất vài giây</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>  
    @push('scripts')
    <script>
    // Biến đếm cho các mục dynamic
    let objectiveCount = 0;
    let responsibilityCount = 0;
    const skills = new Set();

    // Thêm mục tiêu mới
    function addObjective() {
        const container = document.getElementById('objectivesContainer');
        
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="text" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                   placeholder="Nhập mục tiêu công việc">
            <button type="button" 
                    onclick="this.parentElement.remove(); updateObjectives()" 
                    class="text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(div);
        updateObjectives();
        return div.querySelector('input').focus();
    }

    // Cập nhật giá trị hidden field cho objectives
    function updateObjectives() {
        const container = document.getElementById('objectivesContainer');
        const objectives = Array.from(container.querySelectorAll('input[type="text"]'))
            .map(input => input.value.trim())
            .filter(Boolean);
        
        document.getElementById('objectives').value = JSON.stringify(objectives);
    }

    // Thêm trách nhiệm mới
    function addResponsibility() {
        const container = document.getElementById('responsibilitiesContainer');
        
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="text" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                   placeholder="Nhập trách nhiệm công việc">
            <button type="button" 
                    onclick="this.parentElement.remove(); updateResponsibilities()" 
                    class="text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(div);
        updateResponsibilities();
        return div.querySelector('input').focus();
    }

    // Cập nhật giá trị hidden field cho responsibilities
    function updateResponsibilities() {
        const container = document.getElementById('responsibilitiesContainer');
        const responsibilities = Array.from(container.querySelectorAll('input[type="text"]'))
            .map(input => input.value.trim())
            .filter(Boolean);
        
        document.getElementById('responsibilities').value = JSON.stringify(responsibilities);
    }

    // Xử lý thêm kỹ năng
    document.addEventListener('DOMContentLoaded', function() {
        const skillInput = document.getElementById('skillInput');
        if (skillInput) {
            skillInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const skill = this.value.trim();
                    
                    if (skill && !skills.has(skill)) {
                        skills.add(skill);
                        updateSkillsDisplay();
                        updateSkillsInput();
                    }
                    
                    this.value = '';
                }
            });
        }

        // Khởi tạo
        // Thêm một mục tiêu và trách nhiệm mặc định
        addObjective();
        addResponsibility();
        
        // Ẩn nút Quay lại ở bước đầu
        const prevBtn = document.getElementById('prevStepBtn');
        if (prevBtn) {
            prevBtn.classList.add('hidden');
        }
    });

    // Cập nhật hiển thị kỹ năng
    function updateSkillsDisplay() {
        const container = document.getElementById('skillsContainer');
        if (!container) return;
        
        container.innerHTML = '';
        
        skills.forEach(skill => {
            const skillElement = document.createElement('span');
            skillElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
            skillElement.innerHTML = `
                ${skill}
                <button type="button" 
                        onclick="removeSkill('${skill}')" 
                        class="ml-1.5 inline-flex items-center justify-center h-4 w-4 rounded-full text-blue-400 hover:bg-blue-200 hover:text-blue-500">
                    <span class="sr-only">Xóa kỹ năng</span>
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                </button>
            `;
            container.appendChild(skillElement);
        });
    }

    // Xóa kỹ năng
    window.removeSkill = function(skill) {
        skills.delete(skill);
        updateSkillsDisplay();
        updateSkillsInput();
    }

    // Cập nhật giá trị hidden field cho skills
    function updateSkillsInput() {
        const skillsInput = document.getElementById('skills');
        if (skillsInput) {
            skillsInput.value = JSON.stringify(Array.from(skills));
        }
    }

    // Validate form trước khi submit
    const jobForm = document.getElementById('jobForm');
    if (jobForm) {
        jobForm.addEventListener('submit', function(e) {
            // Kiểm tra các trường bắt buộc
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                    
                    // Thêm thông báo lỗi nếu chưa có
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                        const errorMessage = document.createElement('p');
                        errorMessage.className = 'mt-1 text-sm text-red-600';
                        errorMessage.textContent = 'Trường này là bắt buộc';
                        field.parentNode.insertBefore(errorMessage, field.nextSibling);
                    }
                } else {
                    field.classList.remove('border-red-500');
                    // Xóa thông báo lỗi nếu có
                    if (field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')) {
                        field.nextElementSibling.remove();
                    }
                }
            });
            
            // Kiểm tra ít nhất một mục tiêu
            const objectives = JSON.parse(document.getElementById('objectives').value || '[]');
            if (objectives.length === 0) {
                alert('Vui lòng thêm ít nhất một mục tiêu công việc');
                isValid = false;
            }
            
            // Kiểm tra ít nhất một trách nhiệm
            const responsibilities = JSON.parse(document.getElementById('responsibilities').value || '[]');
            if (responsibilities.length === 0) {
                alert('Vui lòng thêm ít nhất một trách nhiệm công việc');
                isValid = false;
            }
            
            // Kiểm tra ít nhất một kỹ năng
            if (skills.size === 0) {
                alert('Vui lòng thêm ít nhất một kỹ năng yêu cầu');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Xử lý chuyển bước form
    let currentStep = 1;
    const totalSteps = 2;

    const nextStepBtn = document.getElementById('nextStepBtn');
    if (nextStepBtn) {
        nextStepBtn.addEventListener('click', function() {
            // Validate bước hiện tại
            if (currentStep === 1) {
                const step1Fields = document.querySelectorAll('#step1 [required]');
                let isStepValid = true;
                
                step1Fields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        isStepValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                if (!isStepValid) {
                    alert('Vui lòng điền đầy đủ thông tin bắt buộc');
                    return;
                }
            }
            
            // Chuyển bước
            if (currentStep < totalSteps) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                document.getElementById(`step${currentStep}`).classList.add('hidden');
                
                currentStep++;
                document.getElementById(`step${currentStep}`).classList.remove('hidden');
                document.getElementById(`step${currentStep}`).classList.add('active');
                
                // Cập nhật nút
                if (currentStep === totalSteps) {
                    this.innerHTML = 'Đăng tin <i class="fas fa-paper-plane ml-2"></i>';
                }
                
                // Hiển thị nút Quay lại
                document.getElementById('prevStepBtn').classList.remove('hidden');
            } else {
                // Nếu là bước cuối, submit form
                document.getElementById('jobForm').dispatchEvent(new Event('submit'));
            }
        });
    }

    const prevStepBtn = document.getElementById('prevStepBtn');
    if (prevStepBtn) {
        prevStepBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                document.getElementById(`step${currentStep}`).classList.add('hidden');
                
                currentStep--;
                document.getElementById(`step${currentStep}`).classList.remove('hidden');
                document.getElementById(`step${currentStep}`).classList.add('active');
                
                // Cập nhật nút
                if (currentStep === 1) {
                    document.getElementById('nextStepBtn').innerHTML = 'Tiếp theo <i class="fas fa-arrow-right ml-2"></i>';
                }
                
                // Ẩn nút Quay lại nếu ở bước đầu
                if (currentStep === 1) {
                    this.classList.add('hidden');
                }
            }
        });
    }
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
    </script>
    <script src="{{ asset('build/js/job-description-editor.js') }}"></script>

    @endpush
</x-app-layout>     