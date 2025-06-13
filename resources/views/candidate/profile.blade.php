<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

            {{ __('Candidate Profile') }}

        </h2>

    </x-slot>



    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('candidate.cv.analysis') }}" class="btn btn-info mb-4">Xem thông tin phân tích CV</a>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))

                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">

                            <span class="block sm:inline">{{ session('success') }}</span>

                        </div>

                    @endif

                    

                    @if (session('error'))

                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">

                            <span class="block sm:inline">{{ session('error') }}</span>

                        </div>

                    @endif

                    

                    <form action="/candidate/profile" method="POST" enctype="multipart/form-data" class="space-y-6" id="profileForm">

                        @csrf

                        <div>

                            <x-input-label for="full_name" :value="__('Full Name')" />

                            <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name', $user->full_name)" required autofocus />

                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />

                        </div>

                        

                        <div>

                            <x-input-label for="email" :value="__('Email')" />

                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />

                            <x-input-error :messages="$errors->get('email')" class="mt-2" />

                        </div>

                        

                        <div>

                            <x-input-label for="phone" :value="__('Phone Number')" />

                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $user->phone)" />

                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />

                        </div>

                        

                        <div>

                            <x-input-label for="skills" :value="__('Skills')" />

                            <textarea id="skills" name="skills" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">{{ old('skills', isset($resume) ? $resume->skills : '') }}</textarea>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Liá»‡t kÃª cÃ¡c ká»¹ nÄƒng cá»§a báº¡n, phÃ¢n cÃ¡ch báº±ng dáº¥u pháº©y.</p>

                            <x-input-error :messages="$errors->get('skills')" class="mt-2" />

                        </div>

                        

                        <div>

                            <x-input-label for="experience" :value="__('Work Experience')" />

                            <textarea id="experience" name="experience" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" rows="4">{{ old('experience', isset($resume) ? $resume->experience : '') }}</textarea>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">MÃ´ táº£ kinh nghiá»‡m lÃ m viá»‡c cá»§a báº¡n, bao gá»“m cÃ´ng ty, vá»‹ trÃ­ vÃ  thá»i gian lÃ m viá»‡c.</p>

                            <x-input-error :messages="$errors->get('experience')" class="mt-2" />

                        </div>

                        

                        <div>

                            <x-input-label for="education" :value="__('Education')" />

                            <textarea id="education" name="education" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" rows="4">{{ old('education', isset($resume) ? $resume->education : '') }}</textarea>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Liá»‡t kÃª cÃ¡c báº±ng cáº¥p, trÆ°á»ng há»c vÃ  thá»i gian há»c.</p>

                            <x-input-error :messages="$errors->get('education')" class="mt-2" />

                        </div>

                        

                        <div>

                            <x-input-label for="resume" :value="__('Resume (PDF or DOCX)')" />

                            <input type="file" id="resume" name="resume" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" required>

                            <x-input-error :messages="$errors->get('resume')" class="mt-2" />

                        </div>

                        

                        <div class="flex items-center justify-end mt-4">

                            <x-primary-button class="ms-4">

                                {{ __('Update Profile') }}

                            </x-primary-button>

                        </div>

                    </form>

                </div>

            </div>

            

            <!-- Display Resume -->

            @if(isset($resume) && isset($resume->cvFile))

                <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <h3 class="text-lg font-semibold mb-4">{{ __('CV cá»§a báº¡n') }}</h3>

                        

                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                                <thead class="bg-gray-50 dark:bg-gray-700">

                                    <tr>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Title') }}</th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('File Name') }}</th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Skills') }}</th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Education') }}</th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Experience') }}</th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date Uploaded') }}</th>

                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>

                                    </tr>

                                </thead>

                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

                                    <tr>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $resume->title }}</td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">

                                            @if($resume->cvFile)

                                                {{ $resume->cvFile->file_name }}

                                            @else

                                                {{ __('File not found') }}

                                            @endif

                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $resume->skills }}</td>

                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $resume->education }}</td>

                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $resume->experience }}</td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $resume->created_at->format('Y-m-d') }}</td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                            @if($resume->cvFile)

                                                <div class="flex flex-col space-y-2">

                                                    <a href="{{ asset('storage/' . $resume->cvFile->file_url) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">

                                                        {{ __('View') }}

                                                    </a>

                                                </div>

                                            @endif

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                        

                        <!-- ThÃªm button parsing CV vÃ o pháº§n hiá»ƒn thá»‹ CV, trÆ°á»›c pháº§n Ä‘Ã³ng </div> cuá»‘i cÃ¹ng -->

                        @if(isset($resume) && isset($resume->cvFile))

                            <div class="mt-6 flex flex-wrap gap-4">

                                @if($resume->parsing_status != 'processing')

                                    <form action="{{ route('candidate.parse.cv') }}" method="POST" id="parseForm">

                                        @csrf

                                        <input type="hidden" name="resume_id" value="{{ $resume->id }}">

                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">

                                            {{ __('PhÃ¢n tÃ­ch CV') }}

                                        </button>

                                    </form>

                                @endif



                                @if($resume->parsing_status == 'completed')

                                    <a href="{{ route('profile.resume.analysis') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">

                                        {{ __('Xem káº¿t quáº£ phÃ¢n tÃ­ch') }}

                                    </a>

                                @elseif($resume->parsing_status == 'processing')

                                    <span class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">

                                        {{ __('Äang phÃ¢n tÃ­ch CV...') }}

                                    </span>

                                @elseif($resume->parsing_status == 'error')

                                    <span class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">

                                        {{ __('Lá»—i phÃ¢n tÃ­ch CV') }}

                                    </span>

                                    <span class="text-sm text-red-600">{{ $resume->parsing_error }}</span>

                                @else

                                    <span class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">

                                        {{ __('Chá» phÃ¢n tÃ­ch CV') }}

                                    </span>

                                @endif

                            </div>

                        @endif

                    </div>

                </div>

            @endif

        </div>

    </div>



    <!-- ThÃªm script Ä‘á»ƒ debug form -->

    <script>

    document.getElementById('parseForm').addEventListener('submit', function(e) {

        console.log('Form Ä‘ang Ä‘Æ°á»£c submit...');

    });

    </script>

</x-app-layout>