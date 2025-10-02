<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;

        $appointments = $doctor->appointments()
            ->with('patient.user')
            ->where('status', 'accepted') // approved only
            ->whereDate('appointment_date', '>=', Carbon::today())
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('doctor.reminders', compact('appointments'));
    }
}
