<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobs;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class RecruiterController extends Controller
{

    // Hiển thị danh sách công việc
    public function index()
    {
        $jobs = Jobs::where('recruiter_id', auth()->id())
                  ->latest()
                  ->paginate(10);
        return view('recruiter.jobs.index', compact('jobs'));
    }

    // Trang Dashboard
    public function dashboard()
    {
        $user = auth()->user();
        $totalJobs = $user->jobs()->count();
        $recentJobs = $user->jobs()
                         ->latest()
                         ->take(5)
                         ->get();

        return view('recruiter.dashboard', [
            'totalJobs' => $totalJobs,
            'recentJobs' => $recentJobs
        ]);
    }



    public function createJob()
    {
        return view('recruiter.jobs.create');
    }



    /**
     * Store a newly created job in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:jobs,code',
            'location' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'required|string',
            'skills' => 'required|array',
            'objectives' => 'required|array',
            'responsibilities' => 'required|array',
            'minimum_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0|gt:minimum_salary',
            'work_location' => 'required|string|in:fulltime,parttime,remote,hybrid',
            'experience' => 'required|string|in:intern,fresher,junior,middle,senior,manager',
            'type' => 'required|string|in:fulltime,parttime,contract,internship,freelance'
        ]);

        // Xử lý dữ liệu trước khi lưu
        $jobData = array_merge($validated, [
            'recruiter_id' => Auth::id(),
            'skills' => $validated['skills'],
            'objectives' => $validated['objectives'],
            'responsibilities' => $validated['responsibilities']
        ]);

        // Tạo công việc mới
        $job = Jobs::create($jobData);

        return redirect()->route('recruiter.jobs.index')
            ->with('success', 'Đăng tin tuyển dụng thành công!');
    }
}