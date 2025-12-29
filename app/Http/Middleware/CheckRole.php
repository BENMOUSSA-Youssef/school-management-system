<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has a valid role
        if ($user->role === null || ($user->role !== 'teacher' && $user->role !== 'student')) {
            // User has no valid role - logout and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Your account role is not set. Please contact administrator.');
        }
        
        if ($role === 'teacher' && $user->role !== 'teacher') {
            // If student tries to access teacher route, redirect to student dashboard
            if ($user->role === 'student') {
                return redirect()->route('student.dashboard')->with('error', 'Access denied. Teacher only.');
            }
            // If role is invalid, logout and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login')->with('error', 'Invalid account role.');
        }

        if ($role === 'student' && $user->role !== 'student') {
            // If teacher tries to access student route, redirect to teacher dashboard
            if ($user->role === 'teacher') {
                return redirect()->route('dashboard')->with('error', 'Access denied. Student only.');
            }
            // If role is invalid, logout and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login')->with('error', 'Invalid account role.');
        }

        return $next($request);
    }
}
