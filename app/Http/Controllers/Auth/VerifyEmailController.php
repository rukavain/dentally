<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    // public function __invoke(EmailVerificationRequest $request): RedirectResponse
    // {
    //     if ($request->user()->hasVerifiedEmail()) {
    //         return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    //     }

    //     if ($request->user()->markEmailAsVerified()) {
    //         event(new Verified($request->user()));
    //     }
        
    //     $obj = User::find($request->user()->id);
    //     $obj->status = 'active';
    //     $obj->save();
        
    //     return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    // }

    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectBasedOnRole($request->user());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        
        $obj = User::find($request->user()->id);
        $obj->status = 'active';
        $obj->save();
        
        return $this->redirectBasedOnRole($request->user());
    }

    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            case 'dentist':
                return redirect()->route('dentist.dashboard', $user->dentist_id);
            default:
                return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }
    }
}
