<?php

namespace App\Http\Controllers;

use Symfony\Component\Translation\Extractor\PhpAstExtractor;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // Hiển thị form đăng tin
    public function create()
    {
        return view('recruiter.jobs.create');
    }

    // Xử lý lưu tin mới
    public function store(Request $request)
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

    }
}
