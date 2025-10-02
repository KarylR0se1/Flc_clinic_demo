<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login if authenticated() does not return a response.
     */
    protected $redirectTo = '/redirect-by-role';

    public function __construct()
    {
        // Guests only except logout
        $this->middleware('guest')->except('logout');

        // Ensure only authenticated users can logout
        $this->middleware('auth')->only('logout');
    }

    /**
     * Called after successful login.
     * - Blocks unapproved doctors from logging in
     * - Blocks users who haven't verified email
     * - Redirects based on role
     */
    protected function authenticated(Request $request, $user)
    {
        

        // Require email verification for all users
        // if (method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail()) {
            //Auth::logout();
            //$request->session()->invalidate();
            //$request->session()->regenerateToken();

            //return redirect()->route('login')->withErrors([
            //    'email' => 'Please verify your email address before logging in.',
            //]);
        //}

        // Redirect based on role
        $role = $user->role ?? 'patient';

        return match ($role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            default  => redirect()->route('patient.dashboard'),
        };
    }
    protected function sendFailedLoginResponse(Request $request)
{
    $user = \App\Models\User::where($this->username(), $request->{$this->username()})->first();

    if ($user) {
        // User exists, password is wrong
        $message = 'The password you entered is incorrect.';
    } else {
        // User does not exist
        $message = 'No account found with that email address.';
    }

    return redirect()->back()
        ->withInput($request->only($this->username(), 'remember'))
        ->with('login_error', $message); // store error in session
}

}
