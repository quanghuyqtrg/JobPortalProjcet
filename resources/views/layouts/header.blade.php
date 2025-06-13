<header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @php
                            $accountType = auth()->user()->account_type ?? null;
                        @endphp
                        @switch(ucfirst($accountType))
                            @case('Candidate')
                                <!-- Links dành riêng cho Candidate -->
                                <x-nav-link :href="route('candidate.profile')" :active="request()->is('candidate/profile')">
                                    {{ __('Candidate Profile') }}
                                </x-nav-link>
                                <x-nav-link href="/candidate/jobs" :active="request()->is('candidate/jobs')">
                                    {{ __('Candidate Jobs') }}
                                </x-nav-link>
                            @break

                            @case('Recruiter')
                                <!-- Links dành riêng cho Recruiter -->
                                <x-nav-link :href="route('recruiter.dashboard')" :active="request()->is('recruiter/dashboard')">
                                    {{ __('Recruiter Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('recruiter.jobs.create')" :active="request()->is('recruiter/jobs/create')">
                                    {{ __('Tạo tin mới') }}
                                </x-nav-link>
                            @break

                            @case('Admin')
                                <!-- Links dành riêng cho Admin -->
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->is('admin/dashboard')">
                                    {{ __('Admin Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.users')" :active="request()->is('admin/users')">
                                    {{ __('Manage Users') }}
                                </x-nav-link>
                            @break

                            @default
                                <!-- Nếu không phải bất kỳ loại tài khoản nào, hiển thị login/register -->
                                <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                                    {{ __('Login') }}
                                </x-nav-link>
                                <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                                    {{ __('Register') }}
                                </x-nav-link>
                        @endswitch
                    @else
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                            {{ __('Login') }}
                        </x-nav-link>
                        <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                            {{ __('Register') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            @auth
            <!-- Profile and Logout Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->full_name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth
        </div>
    </div>
</header>
