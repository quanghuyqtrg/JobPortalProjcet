<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900 leading-tight">
                {{ __('Tổng quan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Thống kê nhanh -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Tổng số công việc -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <i class="fas fa-briefcase text-white text-xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Tổng số công việc
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            {{ $totalJobs }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('recruiter.jobs.index') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                Xem tất cả
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Công việc đang mở -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Đang tuyển dụng
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            {{ $recentJobs->where('is_active', true)->count() }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('recruiter.jobs.index', ['status' => 'active']) }}" class="font-medium text-green-600 hover:text-green-500">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Công việc đã đóng -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <i class="fas fa-pause-circle text-white text-xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Đã tạm dừng
                                    </dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            {{ $recentJobs->where('is_active', false)->count() }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('recruiter.jobs.index', ['status' => 'inactive']) }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách công việc gần đây -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Công việc gần đây
                        </h3>
                        <a href="{{ route('recruiter.jobs.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Đăng tin mới
                        </a>
                    </div>
                </div>

                @if($recentJobs->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($recentJobs as $job)
                            <li class="hover:bg-gray-50">
                                <a href="{{ route('recruiter.jobs.edit', $job->id) }}" class="block">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-blue-600 truncate">
                                                {{ $job->title }}
                                            </p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $job->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $job->is_active ? 'Đang tuyển' : 'Đã đóng' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i>
                                                    {{ $job->location ?? 'Không xác định' }}
                                                </p>
                                                <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                    <i class="fas fa-money-bill-wave mr-1.5 text-gray-400"></i>
                                                    {{ $job->salary ?? 'Thương lượng' }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <i class="far fa-clock mr-1.5 text-gray-400"></i>
                                                Đăng {{ $job->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:justify-end">
                            <a href="{{ route('recruiter.jobs.index') }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Xem tất cả công việc
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-briefcase text-4xl text-gray-400 mb-4"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có công việc nào</h3>
                        <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách tạo công việc mới.</p>
                        <div class="mt-6">
                            <a href="{{ route('recruiter.jobs.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus -ml-1 mr-2 h-5 w-5"></i>
                                Đăng tin mới
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>