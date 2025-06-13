<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Jobs;

class User extends Authenticatable
{
    use Notifiable;

    // Các trường có thể gán giá trị hàng loạt
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone',
        'account_type',
        'status',
        'email_verified_at',
        'remember_token',
    ];

    // Ẩn các trường này khi serialize (ví dụ khi trả về API)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Kiểu dữ liệu cho các trường
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Quan hệ 1-n với Resume (nếu có)
    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    // Quan hệ 1-n với CvFile (nếu có)
    public function cvFiles()
    {
        return $this->hasMany(CvFile::class);
    }

    // Optional: kiểm tra user có phải là ứng viên?
    public function isCandidate()
    {
        return $this->account_type === 'Candidate';
    }

    // Optional: kiểm tra user có phải là admin công ty?
    public function isCompanyAdmin()
    {
        return $this->account_type === 'CompanyAdmin';
    }


    // Relationship với bảng jobs
    public function jobs()
    {
        return $this->hasMany(Jobs::class, 'recruiter_id');
    }
    // Optional: kiểm tra trạng thái hoạt động
    public function isActive()
    {
        return $this->status === 'Active';
    }
}
