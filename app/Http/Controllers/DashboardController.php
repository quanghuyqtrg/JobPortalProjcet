<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobs;
use Illuminate\Support\Facades\Auth;

class RecruiterController extends Controller
{
    // Trang Dashboard
    public function dashboard()
    {
        return view('recruiter.dashboard');
    }
    
    /**
     * Hiển thị danh sách công việc của recruiter
     */
    public function jobs()
    {
        $jobs = Jobs::where('recruiter_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('recruiter.jobs.index', compact('jobs'));
    }
    // Hiển thị form tạo tin mới
    public function createJob()
    {
        return view('recruiter.jobs.create');
    }

    // Lưu tin tuyển dụng mới
    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'location' => 'required',
            'salary' => 'nullable|max:255',
            'type' => 'nullable|max:100'
        ]);

        Jobs::create([
            'recruiter_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary' => $request->salary,
            'type' => $request->type
        ]);

        return redirect()->route('recruiter.jobs')->with('success', 'Đăng tin thành công!');
    }
}
