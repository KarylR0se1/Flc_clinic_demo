<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class PatientRegisterController extends Controller
{
    // Show registration form
    public function showRegistrationForm()
    {
        return view('auth.register-patient');
    }

    // Handle registration
    public function register(Request $request)
    {
        // 1️⃣ Validate input
        $data = $request->validate([
            'first_name'  => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users',
            'password'    => ['required','confirmed', Password::min(8)],
            'sex'         => 'required',
            'birthdate'         => 'required|date',
            
            'address'     => 'required|string|max:255',
            'terms'       => 'accepted',
        ]);

        // 2️⃣ Create User account
        $user = User::create([
            'name'     => $data['first_name'] 
                        . ($data['middle_name'] ? ' '.$data['middle_name'] : '') 
                        . ' ' . $data['last_name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'patient',
        ]);

        // 3️⃣ Create Patient profile linked to User
        try {
    Patient::create([
        'user_id'     => $user->id,
        'first_name'  => $data['first_name'],
        'middle_name' => $data['middle_name'] ?? null,
        'last_name'   => $data['last_name'],
        'birthdate'   => $data['birthdate'],
        'sex'         => $data['sex'],

        'address'     => $data['address'],
    ]);
} catch (\Exception $e) {
    dd($e->getMessage());
}


        // Fire email verification
    event(new Registered($user));

    // Log in the user
    Auth::login($user);

    // Redirect to email verification notice
    return redirect()->route('verification.notice')
                         ->with('status', 'Registration successful. Please verify your email.');
    }
}
