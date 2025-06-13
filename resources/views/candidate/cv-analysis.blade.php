<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Phân tích CV') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($candidateAnalyze)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Thông tin phân tích từ CV</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><strong>Họ tên:</strong> {{ $candidateAnalyze->fullname ?? 'N/A' }}</div>
                                <div><strong>Email:</strong> {{ $candidateAnalyze->email ?? 'N/A' }}</div>
                                <div><strong>Số điện thoại:</strong> {{ $candidateAnalyze->phone_number ?? 'N/A' }}</div>

                                {{-- Hiển thị kỹ năng --}}
                                <div class="md:col-span-2">
                                    <strong>Kỹ năng:</strong>
                                    @php
                                        $skills = is_array($candidateAnalyze->skills) ? $candidateAnalyze->skills : json_decode($candidateAnalyze->skills, true);
                                    @endphp
                                    @if(is_array($skills) && isset($skills['technical_skills']))
                                        <ul class="list-disc list-inside ml-4">
                                            @foreach($skills['technical_skills'] as $level => $skillList)
                                                @if(!empty($skillList))
                                                    <li>
                                                        <strong>{{ ucfirst($level) }}:</strong>
                                                        {{ implode(', ', $skillList) }}
                                                    </li>
                                                @endif
                                            @endforeach
                                            @if(isset($skills['soft_skills']) && !empty($skills['soft_skills']))
                                                <li>
                                                    <strong>Kỹ năng mềm:</strong>
                                                    {{ implode(', ', $skills['soft_skills']) }}
                                                </li>
                                            @endif
                                        </ul>
                                    @else
                                        <span>Không có dữ liệu.</span>
                                    @endif
                                </div>

                                {{-- Hiển thị kinh nghiệm --}}
                                <div class="md:col-span-2">
                                    <strong>Kinh nghiệm:</strong>
                                    @php
                                        $experiences = is_array($candidateAnalyze->experience) ? $candidateAnalyze->experience : json_decode($candidateAnalyze->experience, true);
                                    @endphp
                                    @if(is_array($experiences) && count($experiences))
                                        <ul class="list-disc list-inside ml-4">
                                            @foreach($experiences as $exp)
                                                <li class="mb-2">
                                                    <div><strong>Vị trí:</strong> {{ $exp['job_title'] ?? '' }}</div>
                                                    <div><strong>Công ty:</strong> {{ $exp['organization'] ?? '' }}</div>
                                                    <div><strong>Thời gian:</strong>
                                                        {{ ($exp['employment_dates']['start_date'] ?? '') . ' - ' . ($exp['employment_dates']['end_date'] ?? '') }}
                                                    </div>
                                                    @if(!empty($exp['responsibilities']))
                                                        <div><strong>Nhiệm vụ:</strong>
                                                            <ul class="list-disc list-inside ml-6">
                                                                @foreach($exp['responsibilities'] as $res)
                                                                    <li>{{ $res }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span>Không có dữ liệu.</span>
                                    @endif
                                </div>

                                {{-- Hiển thị học vấn --}}
                                <div class="md:col-span-2">
                                    <strong>Học vấn:</strong>
                                    @php
                                        $educations = is_array($candidateAnalyze->education) ? $candidateAnalyze->education : json_decode($candidateAnalyze->education, true);
                                    @endphp
                                    @if(is_array($educations) && count($educations))
                                        <ul class="list-disc list-inside ml-4">
                                            @foreach($educations as $edu)
                                                <li>
                                                    <div><strong>Bằng cấp:</strong> {{ $edu['degree'] ?? '' }}</div>
                                                    <div><strong>Trường:</strong> {{ $edu['institution'] ?? '' }}</div>
                                                    <div><strong>Ngày tốt nghiệp:</strong> {{ $edu['graduation_date'] ?? '' }}</div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span>Không có dữ liệu.</span>
                                    @endif
                                </div>

                                {{-- Hiển thị dự án (nếu có) --}}
                                <div class="md:col-span-2">
                                    <strong>Dự án:</strong>
                                    @php
                                        $projects = is_array($candidateAnalyze->projects) ? $candidateAnalyze->projects : json_decode($candidateAnalyze->projects, true);
                                    @endphp
                                    @if(is_array($projects) && count($projects))
                                        <ul class="list-disc list-inside ml-4">
                                            @foreach($projects as $project)
                                                <li>
                                                    <div><strong>Tên dự án:</strong> {{ $project['project_name'] ?? '' }}</div>
                                                    <div><strong>Mô tả:</strong> {{ $project['description'] ?? '' }}</div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span>Không có dữ liệu.</span>
                                    @endif
                                </div>

                                <div><strong>Tổng số năm kinh nghiệm:</strong> {{ $candidateAnalyze->total_years_of_experience ?? 'N/A' }}</div>
                                <div><strong>Tóm tắt:</strong> {{ $candidateAnalyze->proposition ?? 'N/A' }}</div>

                                {{-- Hiển thị liên hệ khác --}}
                                <div class="md:col-span-2">
                                    <strong>Liên hệ khác:</strong>
                                    @php
                                        $otherContacts = is_array($candidateAnalyze->other_contacts) ? $candidateAnalyze->other_contacts : json_decode($candidateAnalyze->other_contacts, true);
                                    @endphp
                                    @if(is_array($otherContacts) && count($otherContacts))
                                        <ul class="list-disc list-inside ml-4">
                                            @foreach($otherContacts as $type => $contact)
                                                <li>{{ ucfirst($type) }}: {{ $contact }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span>Không có dữ liệu.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <p>Bạn chưa có dữ liệu phân tích CV.</p>
                    @endif
                    <a href="{{ route('candidate.profile') }}" class="btn btn-secondary mt-3">Quay lại hồ sơ ứng viên</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
