<?php
namespace App\Http\Controllers;
use App\Models\Appointment;

use App\Models\Doctor;  // import the Doctor model
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
public function dashboard()
{
    $user = Auth::user();

    // Get all doctors (for display)
    $doctors = Doctor::all();

    // Get upcoming approved appointments for this patient
    $tomorrow = \Carbon\Carbon::tomorrow()->startOfDay();

    $appointments = Appointment::where('patient_id', $user->patient->id ?? 0)
        ->where('status', 'accepted') // approved appointments
        ->whereDate('appointment_date', '>=', $tomorrow)
        ->with('doctor.user')
        ->orderBy('appointment_date')
        ->get();

    // Count reminders
    $reminderCount = $appointments->count();

    return view('patient.dashboard', compact('doctors', 'appointments', 'reminderCount'));
}



}
