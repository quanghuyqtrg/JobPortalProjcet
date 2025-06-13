<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Header extends Component
{
    public $accountType;

    public function __construct()
    {
        // Lấy thông tin account_type từ user đã đăng nhập
        $this->accountType = Auth::user() ? Auth::user()->account_type : 'guest';
    }

    public function render()
    {
        return view('components.header');
    }
}
