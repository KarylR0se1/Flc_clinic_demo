<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminPatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->get();
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'sex'        => 'required',
            'birthdate'  => 'required|date',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'required|string|max:255', // added
        ]);

        // create user account
        $user = User::create([
            'name'     => $validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name'],
            'email'    => $validated['email'],
            'password' => Hash::make('password123'), // default password
            'role'     => 'patient',
        ]);

        // create patient profile
        Patient::create([
            'user_id'     => $user->id,
            'first_name'  => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name'   => $validated['last_name'],
            'sex'         => $validated['sex'],
            'birthdate'   => $validated['birthdate'],
            'phone_number'=> $validated['phone'] ?? null,
            'address'     => $validated['address'], // added
        ]);

        return redirect()->route('admin.patients.index')
                         ->with('success', 'Patient account created successfully.');
    }

    public function show(Patient $patient)
    {
        return view('admin.patients.show', compact('patient'));
    }
}
