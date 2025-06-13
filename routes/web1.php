<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Auth;
use App\Models\Resume;
use App\Http\Controllers\RecruiterController;
use App\Http\Controllers\AdminController;


// Route trang chủ
Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Đảm bảo chỉ người dùng với account_type=candidate mới có thể vào các route của candidate
Route::middleware(['auth', 'account_type:candidate'])->group(function () {
    Route::get('/candidate/profile', [CandidateController::class, 'showProfileForm'])->name('candidate.profile');
    Route::get('/candidate/cv-analysis', [CandidateController::class, 'showCvAnalysis'])->name('candidate.cv.analysis');
    // Các route khác cho candidate
});

// Đảm bảo chỉ người dùng với account_type=recruiter mới có thể vào các route của recruiter
Route::middleware(['auth', 'account_type:recruiter'])->group(function () {
    Route::get('/recruiter/dashboard', [RecruiterController::class, 'dashboard'])->name('recruiter.dashboard');
    
    // Quản lý công việc
    Route::prefix('recruiter/jobs')->group(function() {
        Route::get('/', [RecruiterController::class, 'index'])->name('recruiter.jobs.index');
        Route::get('/create', [RecruiterController::class, 'createJob'])->name('recruiter.jobs.create');
        Route::post('/', [RecruiterController::class, 'storeJob'])->name('recruiter.jobs.store');
        
        // Thêm các route mới
        Route::get('/{job}/edit', [RecruiterController::class, 'editJob'])->name('recruiter.jobs.edit');
        Route::put('/{job}', [RecruiterController::class, 'updateJob'])->name('recruiter.jobs.update');
        Route::delete('/{job}', [RecruiterController::class, 'deleteJob'])->name('recruiter.jobs.destroy');
    });
    
    // Gợi ý kỹ năng
    Route::post('/recruiter/suggest-skills', [RecruiterController::class, 'suggestSkills'])
        ->name('recruiter.suggest.skills');
        
    // API cho job description
    Route::post('/recruiter/api/generate-description', [RecruiterController::class, 'generateJobDescription'])
        ->name('recruiter.api.generate-description');

    // API tái tạo thẻ
    Route::post('/recruiter/api/regenerate-tag/{tagType}', [RecruiterController::class, 'regenerateTag'])
        ->where('tagType', 'skills|requirements|objectives')
        ->name('recruiter.api.regenerate-tag');

    // API lấy thông tin meta
    Route::post('/recruiter/api/fetch-job-meta', [RecruiterController::class, 'fetchJobMeta'])
        ->name('recruiter.api.fetch-job-meta');
});

// Đảm bảo chỉ người dùng với account_type=admin mới có thể vào các route của admin
Route::middleware(['auth', 'account_type:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    // Các route khác cho admin
});

// Thêm route mới cho API gợi ý kỹ năng
Route::middleware(['auth'])->group(function () {
    // ... các route khác ...
    Route::post('/api/skills/suggest', [RecruiterController::class, 'suggestSkills'])
        ->name('api.skills.suggest');
});


// Dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        if ($user->account_type === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->account_type === 'recruiter') {
            return redirect()->route('recruiter.dashboard');
        } elseif ($user->account_type === 'candidate') {
            return redirect()->route('candidate.dashboard');
        }
        
        // Nếu không phải bất kỳ loại tài khoản nào ở trên
        return redirect('/');
    })->name('dashboard');
});




Route::get('/profile/resume/analysis', [CandidateController::class, 'showResumeAnalysis'])->name('profile.resume.analysis')->middleware('auth');

// Thêm route để parsing CV
Route::post('/candidate/parse-cv', [CandidateController::class, 'parseCV'])->name('candidate.parse.cv');

// Route GET cho truy cập trực tiếp
Route::get('/candidate/start-parsing/{resumeId}', [CandidateController::class, 'startParsingCV'])->name('candidate.start.parsing');



Route::post('/n8n/cvdata1',[CandidateController::class, 'N8NScreenResult']);

Route::get('/candidate/cv-analysis', [CandidateController::class, 'showCvAnalysis'])->name('candidate.cv.analysis')->middleware('auth');

require __DIR__.'/auth.php';
