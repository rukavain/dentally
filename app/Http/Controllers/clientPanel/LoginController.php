<?php

namespace App\Http\Controllers\clientPanel;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        $loggedUser = User::where('email', $request->email)->first();

        if ($loggedUser && $loggedUser->patient_id) {
            session(['patient_id' => $loggedUser->patient_id]);

            return redirect()->route('client.dashboard');
        }

    }
}
