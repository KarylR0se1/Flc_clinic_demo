<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index()
    {
        // Get all upcoming approved appointments
        $appointments = Appointment::with(['doctor', 'patient.user'])
            ->where('status', 'accepted')
            ->whereDate('appointment_date', '>=', Carbon::today())
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('admin.reminders', compact('appointments'));
    }
}
