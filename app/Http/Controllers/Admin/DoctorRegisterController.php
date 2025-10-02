<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DoctorRegisterController extends Controller
{
        public function index()
{
    $doctors = Doctor::with('user')->get(); // Fetch doctors with user info
    return view('admin.doctors.index', compact('doctors'));
}
    // Show registration form
    public function create()
    {
        return view('admin.doctors.create'); // matches your Blade file
    }

    // Store doctor
    public function store(Request $request)
    {
        $request->validate([
            'first_name'       => 'required|string|max:255',
            'middle_name'      => 'nullable|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255|unique:users',
            'password' => [
        'required',
        'confirmed',
        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
    ],
            'specialization'   => 'required|string|max:255',
            'license_number'   => 'required|string|max:50|unique:doctors',
            'schedule.*.day'   => 'required|string',
            'schedule.*.start' => 'required|date_format:H:i',
            'schedule.*.end'   => 'required|date_format:H:i|after:schedule.*.start',
        ]);

        DB::transaction(function () use ($request) {

            // 1️⃣ Create user account
            $user = User::create([
                'name'     => $request->first_name 
                            . ($request->middle_name ? ' '.$request->middle_name : '') 
                            . ' ' . $request->last_name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'doctor',
            ]);

            // 2️⃣ Create doctor profile
            $doctor = Doctor::create([
                'user_id'       => $user->id,
                'first_name'    => $request->first_name,
                'middle_name'   => $request->middle_name ?? null,
                'last_name'     => $request->last_name,
                'specialization'=> $request->specialization,
                'license_number'=> $request->license_number,
            ]);

            // 3️⃣ Save schedules
            foreach ($request->schedule as $sched) {
                DoctorSchedule::create([
                    'doctor_id'  => $doctor->id,
                    'day'        => $sched['day'],
                    'start_time' => $sched['start'],
                    'end_time'   => $sched['end'],
                ]);
            }

        });

        return redirect()->route('admin.doctors.index')
                         ->with('status', 'Doctor registered successfully.');
    }


}
