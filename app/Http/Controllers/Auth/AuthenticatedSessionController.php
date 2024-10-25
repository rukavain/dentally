<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

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
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     if($request->user()->role === 'admin'){
    //         return redirect()->route('admin.dashboard');
    //     } elseif($request->user()->role === 'staff'){
    //         return redirect()->route('receptionist.dashboard');
    //     }   

    //     return redirect()->intended(RouteServiceProvider::HOME);
    // }

    // public function store(LoginRequest $request): RedirectResponse
    // {
   
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);

    //     if (Auth::attempt($request->only('email', 'password'))) {
    //         $request->session()->regenerate();

    //         $user = User::where('email', $request->email)->first();

    //         if ($user && $user->patient_id) {
    //             session(['patient_id' => $user->patient_id]);
    //         }

    //         if($request->user()->role === 'admin'){
    //             return redirect()->route('admin.dashboard');
    //         } elseif($request->user()->role === 'staff'){
    //             return redirect()->route('staff.dashboard');
    //         } elseif($request->user()->role === 'dentist'){
    //             return redirect()->route('dentist.dashboard', Auth::user()->dentist_id);
    //         }   else {
    //             return redirect()->intended(RouteServiceProvider::HOME);

    //         }
    
    //     }

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ]);

    // }

    public function store(LoginRequest $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate();

        $user = Auth::user(); // Get the authenticated user

        // Check if the user's email is verified
        if (!$user->hasVerifiedEmail()) {
            // Send a verification link
            $user->sendEmailVerificationNotification();

            // Optionally, you can redirect to a specific route with a message
            return redirect()->route('verification.notice')->with('status', 'Verification link sent! Please check your email.');
        }

        // Store patient_id in session if it exists
        if ($user->patient_id) {
            session(['patient_id' => $user->patient_id]);
        }

        // Redirect based on user role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        } elseif ($user->role === 'dentist') {
            return redirect()->route('dentist.dashboard', $user->dentist_id);
        } else {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
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
