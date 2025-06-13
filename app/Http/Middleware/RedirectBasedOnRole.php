<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Chỉ redirect khi truy cập route 'dashboard'
            if ($request->routeIs('dashboard')) {
                try {
                    switch ($user->account_type) {
                        case 'candidate':
                            return redirect('/candidate/profile');
                        case 'recruiter':
                            return redirect('/recruiter/dashboard');
                        case 'admin':
                            return redirect('/admin/dashboard');
                        default:
                            return $next($request);
                    }
                } catch (\Exception $e) {
                    Log::error("RedirectBasedOnRole middleware error: " . $e->getMessage());
                    return $next($request);
                }
            }
        }

        return $next($request);
    }
}
