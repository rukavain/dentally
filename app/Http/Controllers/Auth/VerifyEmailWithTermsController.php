<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TermsAcceptance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VerifyEmailWithTermsController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('verification.notice')
                ->with('error', 'Invalid or expired verification link.');
        }
        if (!$request->has('accept_terms')) {
            return back()->withErrors(['accept_terms' => 'You must accept the Terms and Conditions.']);
        }
        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            // Record terms acceptance
            TermsAcceptance::create([
                'user_id' => $user->id,
                'accepted_at' => Carbon::now(),
                'ip_address' => $request->ip(),
                'terms_version' => '1.0', // Update this when terms change
            ]);
            return redirect()->intended(route('dashboard'))
                ->with('status', 'Your email has been verified and Terms accepted.');
        }
        return redirect()->intended(route('dashboard'));
    }
}
