<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    // Doctor dashboard
    public function dashboard()
    {
        $doctor = Doctor::where('user_id', Auth::id())->with('user')->first();

        $appointments = $doctor
            ? $doctor->appointments()
                ->with('patient.user')
                ->orderBy('appointment_date')
                ->get()
            : collect();

        $tomorrow = \Carbon\Carbon::tomorrow()->startOfDay();

        $reminderCount = $doctor
            ? $doctor->appointments()
                ->where('status', 'accepted')
                ->whereDate('appointment_date', '>=', $tomorrow)
                ->count()
            : 0;

        return view('doctor.dashboard', compact('doctor', 'appointments', 'reminderCount'));
    }

    // View doctor schedule
    public function schedule()
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();
        $schedules = $doctor->schedules ?? collect();

        return view('doctor.schedule', compact('schedules'));
    }

    // Update doctor schedule
    public function updateSchedule(Request $request)
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        $doctor->schedules()->delete();

        foreach ($request->input('days', []) as $day => $time) {
            if (!empty($time['start_time']) && !empty($time['end_time'])) {
                Schedule::create([
                    'doctor_id'     => $doctor->id,
                    'available_day' => $day,
                    'start_time'    => $time['start_time'],
                    'end_time'      => $time['end_time'],
                ]);
            }
        }

        return back()->with('success', 'Schedule updated successfully.');
    }

    // Show consultation form
    // Show consultation form
public function show(MedicalRecord $record)
{
    $doctor = auth()->user()->doctor;

    if ($record->doctor_id !== $doctor->id) {
        abort(403, 'Unauthorized action.');
    }

    $appointment = $record->appointment;
    $patient = $record->patient;
    $doctors = Doctor::with('user')->get();

    // Pass family medical history and immunization records
    $familyHistory = [
        'hypertension',
        'diabetes',
        'heart_disease',
        'cancer',
        'tb'
    ];

    // Build array of family history values
    $familyHistoryValues = [];
    foreach ($familyHistory as $fh) {
        $familyHistoryValues[$fh] = $record->{'family_'.$fh} ?? 0;
    }

    $childhoodVaccines = $record->childhood_vaccines ?? null;
    $adultVaccines = $record->adult_vaccines ?? null;

    return view('doctor.records.form', compact(
        'patient',
        'record',
        'doctors',
        'appointment',
        'familyHistoryValues',
        'childhoodVaccines',
        'adultVaccines'
    ));
}


    // Update consultation record
    public function update(Request $request, MedicalRecord $record)
    {
        $doctor = auth()->user()->doctor;

        if ($record->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'reason_of_visit' => 'nullable|string|max:1000',
            'history_of_present_illness' => 'nullable|string|max:2000',
            'examination' => 'nullable|string|max:2000',
            'assessment' => 'nullable|string|max:2000',
            'treatment_plan' => 'nullable|string|max:2000',
           
        ]);

       

        // Ensure patient and doctor IDs are preserved
        $validated['patient_id'] = $request->patient_id ?? $record->patient_id;
        $validated['doctor_id'] = $request->doctor_id ?? $record->doctor_id ?? $doctor->id;
        $validated['appointment_id'] = $request->appointment_id ?? $record->appointment_id;
        $validated['visit_date'] = $request->visit_date ?? $record->visit_date ?? now();

        $record->update($validated);

        return redirect()->route('doctor.records.show', $record->id)
            ->with('success', 'Consultation updated successfully.');
    }
    // Show medical record in view mode
public function showRecord(MedicalRecord $record)
{
    $doctor = auth()->user()->doctor;
    $record->load(['appointment.laboratoryRequests']); // Eager load lab requests

    $patient = $record->patient;
    $appointment = $record->appointment;

    if ($record->doctor_id !== $doctor->id) {
        abort(403, 'Unauthorized action.');
    }

    return view('doctor.records.show', compact('record','patient', 'appointment'));
}

}
