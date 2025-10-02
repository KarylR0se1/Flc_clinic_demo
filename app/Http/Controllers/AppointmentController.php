<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // Dashboard for admin
public function dashboard()
{
    $doctors = Doctor::all();

    // Fetch all appointments with relationships
    $appointments = Appointment::with(['doctor', 'patient'])
        ->latest()
        ->get();

    // Count upcoming approved appointments (tomorrow or later)
    $tomorrow = \Carbon\Carbon::tomorrow()->startOfDay();

    $reminderCount = Appointment::where('status', 'accepted') // approved
        ->whereDate('appointment_date', '>=', $tomorrow)
        ->count();

    return view('admin.dashboard', compact('appointments', 'doctors', 'reminderCount'));
}


    // Show appointment booking form for a specific doctor
    public function create($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        // Key schedules by day_of_week for JS
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->get()
            ->keyBy('day');

        return view('appointments.create', compact('doctor', 'schedules'));
    }

    // Store booked appointment

public function store(Request $request)
{
    $request->validate([
        'doctor_id'        => 'required|exists:doctors,id',
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required|string', // now 12-hour format
    ]);

    $doctorId = $request->doctor_id;
    $date = Carbon::parse($request->appointment_date);
    $time12 = $request->appointment_time;

    // Convert 12-hour time to 24-hour for DB checks
    try {
        $time24 = Carbon::createFromFormat('g:i A', $time12)->format('H:i:s');
    } catch (\Exception $e) {
        return back()->with('error', 'Invalid time format.');
    }

    $dayOfWeek = $date->format('l');

    $schedule = DoctorSchedule::where('doctor_id', $doctorId)
        ->where('day', $dayOfWeek)
        ->first();

    if (!$schedule) {
        return back()->with('error', 'Doctor is not available on that day.');
    }

    // Check if selected time is within schedule
    if ($time24 < $schedule->start_time || $time24 >= $schedule->end_time) {
        return back()->with('error', 'Selected time is outside of doctor\'s available hours.');
    }

    // Max 30 appointments per day
    $appointmentsPerDay = Appointment::where('doctor_id', $doctorId)
        ->where('appointment_date', $date->format('Y-m-d'))
        ->count();

    if ($appointmentsPerDay >= 30) {
        return back()->with('error', 'The doctor has reached the maximum number of appointments for this day.');
    }

    // Max 4 appointments per hour
    $startHour = Carbon::createFromFormat('H:i:s', $time24)->format('H');
    $appointmentsThisHour = Appointment::where('doctor_id', $doctorId)
        ->where('appointment_date', $date->format('Y-m-d'))
        ->whereBetween('appointment_time', ["$startHour:00:00", "$startHour:59:59"])
        ->count();

    if ($appointmentsThisHour >= 4) {
        return back()->with('error', 'This hour is fully booked.');
    }

    // Prevent double-booking exact same time
    $exists = Appointment::where('doctor_id', $doctorId)
        ->where('appointment_date', $date->format('Y-m-d'))
        ->where('appointment_time', $time24)
        ->exists();

    if ($exists) {
        return back()->with('error', 'This time slot is already booked.');
    }

    // Ensure patient profile exists
    $patient = Patient::firstOrCreate(
        ['user_id' => Auth::id()],
        [
            'first_name'   => Auth::user()->name ?? 'Unknown',
            'last_name'    => '',
            'birthdate'    => now(),
            'gender'       => 'Not set',
            'address'      => 'Not set',
            'phone_number' => 'Not set',
        ]
    );

    Appointment::create([
        'doctor_id'        => $doctorId,
        'patient_id'       => $patient->id,
        'appointment_date' => $date->format('Y-m-d'),
        'appointment_time' => $time24, // store in 24-hour in DB
        'status'           => 'pending',
    ]);

    return redirect()->route('appointments.history', $doctorId)
        ->with('success', 'Appointment booked successfully!');
}
    // Patient appointment history
  public function history(Request $request)
{
    $patient = auth()->user()->patient;

    if (!$patient) {
        return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
    }

    // Get the status filter from the request
    $status = $request->get('status', 'all'); // default 'all'

    $appointments = \App\Models\Appointment::where('patient_id', $patient->id)
        ->when($status !== 'all', function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->orderBy('appointment_date', 'desc')
        ->get();

    return view('appointments.history', compact('appointments', 'status'));
}


    // Show reschedule form
    public function edit(Appointment $appointment)
    {
       $doctor = $appointment->doctor;

    // Get doctor's schedule keyed by day_of_week
    $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
        ->get()
        ->keyBy('day');

    // Booked slots excluding current appointment
    $bookedSlots = $doctor->appointments()
        ->where('appointment_date', $appointment->appointment_date)
        ->where('id', '!=', $appointment->id)
        ->pluck('appointment_time')
        ->toArray();
        return view('Appointments.reschedule', compact('appointment', 'schedules', 'bookedSlots'));
    }

    // Update rescheduled appointment
public function updateReschedule(Request $request, Appointment $appointment)
{
    $request->validate([
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required',
    ]);

    // Check conflict excluding this appointment
    $conflict = Appointment::where('doctor_id', $appointment->doctor_id)
        ->where('appointment_date', $request->appointment_date)
        ->where('appointment_time', $request->appointment_time)
        ->where('id', '!=', $appointment->id)
        ->exists();

    if ($conflict) {
        return back()->with('error', 'This time slot is already booked.');
    }

    $appointment->appointment_date = $request->appointment_date;
    $appointment->appointment_time = $request->appointment_time;
    $appointment->status = 'pending'; // make sure 'pending' is in enum
    $appointment->save();

    return redirect()->route('appointments.history', ['status' => 'pending'])
        ->with('success', 'Appointment rescheduled successfully.');
}

// Cancel appointment
public function cancel(Appointment $appointment)
{
    if ($appointment->patient_id !== auth()->user()->patient->id) {
        return back()->with('error', 'You are not authorized to cancel this appointment.');
    }

    if (!in_array($appointment->status, ['pending', 'accepted'])) {
        return back()->with('error', 'This appointment cannot be canceled.');
    }

    $appointment->status = 'cancelled'; // matches enum exactly
    $appointment->save();

    return redirect()->route('appointments.history', ['status' => 'pending'])
        ->with('success', 'Appointment canceled successfully.');
}

// Approve appointment (example if you have this)
public function approve(Appointment $appointment)
{
    $appointment->status = 'approved'; // make sure it matches enum
    $appointment->save();

    return back()->with('success', 'Appointment approved successfully.');
}

public function show($id)
{
    $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);
    return view('admin.appointments.show', compact('appointment'));
}
public function showPatient($id)
{
    $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);
    return view('patient.appointments.show', compact('appointment'));
}
public function confirm(Request $request)
{
    $request->validate([
        'doctor_id' => 'required|exists:doctors,id',
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required|string',
    ]);

    $doctor = Doctor::findOrFail($request->doctor_id);

    return view('appointments.confirm', [
        'doctor' => $doctor,
        'date'   => $request->appointment_date,
        'time'   => $request->appointment_time,
    ]);
}

}
