<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'birthdate'       => 'nullable|date',
            'sex'             => 'nullable|string|max:20',
            'phone_number'    => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
            'specialization'  => 'nullable|string|max:255',
            'license_number'  => 'nullable|string|max:50',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        // Update common fields
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        // Update doctor profile if exists
        if ($user->doctor) {
            $user->doctor->update([
                'specialization' => $request->specialization ?? $user->doctor->specialization,
                'license_number' => $request->license_number ?? $user->doctor->license_number,
                'profile_picture'=> $user->profile_picture,
            ]);
        }

        // Update patient profile if exists
        if ($user->patient) {
            $user->patient->update([
                'birthdate'      => $request->birthdate ?? $user->patient->birthdate,
                'sex'            => $request->sex ?? $user->patient->sex,
                'phone_number'   => $request->phone_number ?? $user->patient->phone_number,
                'address'        => $request->address ?? $user->patient->address,
                'profile_picture'=> $user->profile_picture,
            ]);
        }

        // Update admin profile if exists
        if ($user->admin) {
            $user->admin->update([
                'profile_picture' => $user->profile_picture,
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        return view('profile.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }
}
