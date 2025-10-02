<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\AppointmentReminderNotification;

class AppointmentReminderController extends Controller
{
    /**
     * Trigger reminders for all upcoming approved appointments
     */

public function triggerAll()
{
    $appointments = Appointment::where('status', 'approved')
        ->whereDate('appointment_date', '>=', now())
        ->with(['patient.user', 'doctor.user'])
        ->get();

    if ($appointments->isEmpty()) {
        return redirect()->back()->with('error', 'No upcoming approved appointments found.');
    }

    foreach ($appointments as $appointment) {
        // Patient
        if ($appointment->patient && $appointment->patient->user) {
            $appointment->patient->user->notify(
                new AppointmentReminderNotification($appointment, "Reminder: You have an appointment with Dr. {$appointment->doctor->first_name} {$appointment->doctor->last_name} on {$appointment->appointment_date->format('M d, Y')} at {$appointment->appointment_time}.")
            );
        }

        // Doctor
        if ($appointment->doctor && $appointment->doctor->user) {
            $appointment->doctor->user->notify(
                new AppointmentReminderNotification($appointment, "Reminder: You have an appointment with patient {$appointment->patient->first_name} {$appointment->patient->last_name}.")
            );
        }

        // Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new AppointmentReminderNotification($appointment, "Reminder: Appointment scheduled for {$appointment->appointment_date->format('M d, Y')} at {$appointment->appointment_time}.")
            );
        }
    }

    return redirect()->back()->with('success', 'Reminders sent successfully!');
}


}
