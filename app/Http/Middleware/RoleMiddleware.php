<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, $role): Response
    // {
    //     if (auth()->user()->role !== $role) {
    //         abort(404);
    //         return view('/');
    //     }
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Make sure roles are passed as an array
        $allowedRoles = is_array($roles) ? $roles : explode(',', $roles);

        // Check if the authenticated user's role is one of the allowed roles
        if (!in_array(auth()->user()->role, $allowedRoles)) {
            return abort(403);  // Forbidden (or 404 if you prefer)
        }

        return $next($request);
    }


    

    // public function handle(Request $request, Closure $next, ...$roles)
    // {
    //     $rolesArray = explode(',', $roles); // Convert roles into an array
    
    //     if (auth()->check() && in_array(auth()->user()->role, $rolesArray)) {
    //         return $next($request);
    //     }
        
    //     return redirect('/')->with('error', 'Unauthorized Access');
    // }
}
