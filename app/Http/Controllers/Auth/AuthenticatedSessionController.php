<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Xác thực user bằng LoginRequest (kiểm tra email/password)
        $request->authenticate();

        // Tạo lại session để bảo mật (session fixation protection)
        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect theo loại tài khoản (account_type)
        switch ($user->account_type) {
            case 'candidate':
                return redirect()->route('candidate.profile');
            case 'recruiter':
                return redirect()->route('recruiter.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                // Nếu không khớp loại tài khoản, fallback về route dashboard mặc định
                return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
