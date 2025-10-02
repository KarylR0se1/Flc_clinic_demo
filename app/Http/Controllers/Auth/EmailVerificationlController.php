<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{
    /**
     * Public verification endpoint: verifies a user via a signed URL.
     *
     * Route: /email/verify/{id}/{hash}?signature=...&expires=...
     */
    protected $redirectTo = '/redirect-by-role';

    public function verify(Request $request, $id, $hash)
    {
        // 1) signature check - prevents tampering & enforces expiry
        if (! $request->hasValidSignature()) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid or expired verification link.']);
        }

        // 2) find the user
        $user = User::findOrFail($id);

        // 3) confirm hash matches the user's email
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->withErrors(['email' => 'Verification data does not match.']);
        }

        // 4) already verified?
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Email already verified. You may log in.');
        }

        // 5) mark verified and fire event
        $user->markEmailAsVerified();
        event(new Verified($user));
        
        // 6) success — redirect to login (or auto-login if you prefer)
        return redirect()->route('login')->with('status', 'Email verified — you can now log in.');
    }
}
