<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AccountTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $type  Expected account_type, ví dụ 'candidate', 'recruiter', 'admin'
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (strtolower(Auth::user()->account_type) !== strtolower($type)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
