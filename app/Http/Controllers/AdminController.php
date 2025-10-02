<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorSchedule; 
use App\Models\Appointment;
use App\Models\AppointmentReminder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller {
    public function showDoctorRegistrationForm()
{
    return view('admin.doctors.create'); // create this Blade file
}

public function registerDoctor(Request $request)
{
    $data = $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'middle_name' => ['nullable', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users'],
        'specialization' => ['required', 'string', 'max:255'],
        'license_number' => ['required', 'string', 'max:255', 'unique:doctors,license_number'],
        'schedule' => ['required', 'array'],
        'schedule.*.day' => ['required', 'string'],
        'schedule.*.start' => ['required', 'date_format:H:i'],
        'schedule.*.end' => ['required', 'date_format:H:i'],
    ]);

    DB::transaction(function () use ($data, $request) {
        $fullName = trim("{$data['first_name']} {$data['middle_name']} {$data['last_name']}");

        $user = User::create([
            'name' => $fullName,
            'email' => $data['email'],
            'password' => Hash::make('defaultpassword'), // temporary password
            'role' => 'doctor',
        ]);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'specialization' => $data['specialization'],
            'license_number' => $data['license_number'],
            'is_approved' => true, // admin-approved
        ]);

        foreach ($request->input('schedule') as $scheduleItem) {
            DoctorSchedule::create([
                'doctor_id' => $doctor->id,
                'day' => $scheduleItem['day'],
                'start_time' => $scheduleItem['start'],
                'end_time' => $scheduleItem['end'],
            ]);
        }
    });

    return redirect()->route('admin.doctors.index')
        ->with('status', 'Doctor registered successfully.');
}
public function sendReminder(Appointment $appointment)
{
    $appointment->patient->user->notify(new AppointmentReminder($appointment));
    $appointment->doctor->user->notify(new AppointmentReminder($appointment));
    $appointment->update(['reminder_sent' => true]);

    return back()->with('success', 'Reminder sent successfully!');
}

}

