<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\LaboratoryRequest;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Show all medical records for a patient
     */
    public function index(Patient $patient)
    {
        $patient->load([
            'medicalRecords.doctor.user',
            'medicalRecords.patient.user'
        ]);
        $records = $patient->medicalRecords
            ->sortByDesc('visit_date')
            ->sortByDesc('created_at');

        return view('Admin.Records.index', compact('patient', 'records'));
    }

    /**
     * Show create form for a patient
     */
    public function create($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $doctors = Doctor::with('user')->get();

        return view('admin.records.create', compact('patient', 'doctors'));
    }

    /**
     * Store a new medical record
     */
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'reason_of_visit' => 'nullable|string|max:255',
            'history_of_present_illness' => 'nullable|string',
            'examination' => 'nullable|string',
            'assessment' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'progress_notes' => 'nullable|string',
            'diagnostic_results' => 'nullable|string',
            'past_illnesses' => 'nullable|string',
            'past_surgeries' => 'nullable|string',
            'allergies' => 'nullable|string',
            'pre_conditions' => 'nullable|string',
            'family_history' => 'nullable|string',
            'childhood_vaccines' => 'nullable|string',
            'adult_vaccines' => 'nullable|string',
            'lab_results_file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240', // 10 MB max
            'lab_test_type' => 'nullable|string|max:255',
            'bp' => 'nullable|string|max:20',
            'hr' => 'nullable|string|max:20',
            'rr' => 'nullable|string|max:20',
            'temp' => 'nullable|string|max:20',
            'oxygen_saturation' => 'nullable|string|max:20',
            'weight' => 'nullable|string|max:20',
            'height' => 'nullable|string|max:20',
            'bmi' => 'nullable|string|max:20',
            'physical_exam' => 'nullable|string',
            'visit_date' => 'nullable|date',
        ]);

        $appointment = $request->appointment_id ? Appointment::find($request->appointment_id) : null;

        $record = MedicalRecord::create([
            'patient_id' => $patient->id,
            'appointment_id' => $appointment->id ?? null,
            'doctor_id' => $appointment->doctor_id ?? $request->doctor_id ?? auth()->user()->doctor->id ?? null,
            'visit_date' => $request->visit_date ?? ($appointment->appointment_date ?? now()),
            'reason_of_visit' => $validated['reason_of_visit'] ?? null,
            'history_of_present_illness' => $validated['history_of_present_illness'] ?? null,
            'examination' => $validated['examination'] ?? null,
            'assessment' => $validated['assessment'] ?? null,
            'treatment_plan' => $validated['treatment_plan'] ?? null,
            'current_medications' => $validated['current_medications'] ?? null,
            'progress_notes' => $validated['progress_notes'] ?? null,
            'diagnostic_results' => $validated['diagnostic_results'] ?? null,
            'past_illnesses' => $validated['past_illnesses'] ?? null,
            'past_surgeries' => $validated['past_surgeries'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'pre_conditions' => $validated['pre_conditions'] ?? null,
            'family_history' => $validated['family_history'] ?? null,
            'childhood_vaccines' => $validated['childhood_vaccines'] ?? null,
            'adult_vaccines' => $validated['adult_vaccines'] ?? null,
            'bp' => $validated['bp'] ?? null,
            'hr' => $validated['hr'] ?? null,
            'rr' => $validated['rr'] ?? null,
            'temp' => $validated['temp'] ?? null,
            'oxygen_saturation' => $validated['oxygen_saturation'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'height' => $validated['height'] ?? null,
            'bmi' => $validated['bmi'] ?? null,
            'physical_exam' => $validated['physical_exam'] ?? null,
        ]);

        // Attach laboratory result if uploaded
        if ($request->hasFile('lab_results_file')) {
            $filePath = $request->file('lab_results_file')->store('lab_results', 'public');

            LaboratoryRequest::create([
                'medical_record_id' => $record->id,
                'patient_id' => $patient->id,
                'test_type' => $request->lab_test_type ?? 'Unknown Test',
                'requested_by' => auth()->user()->name,
                'result_file' => $filePath,
                'status' => 'complete',
            ]);
        }

        return redirect()
            ->route('admin.records.show', $record->id)
            ->with('success', 'Medical record saved successfully, laboratory results attached if uploaded.');
    }

    /**
     * Show single medical record
     */
    public function show(MedicalRecord $record)
    {
        $record->load(['patient', 'doctor.user', 'appointment', 'laboratoryRequests']);
        return view('admin.records.show', compact('record'));
    }

    /**
     * Print a medical record
     */
    public function print($id)
    {
        $record = MedicalRecord::with(['patient', 'doctor', 'appointment'])->findOrFail($id);
        $appointment = $record->appointment;

        return view('Admin.Records.print', compact('record', 'appointment'));
    }


    /**
     * Show form for adding vital signs
     */
    public function createVitals($patientId, $appointmentId)
    {
        $patient = Patient::findOrFail($patientId);
        $appointment = Appointment::findOrFail($appointmentId);

        return view('admin.vitals.create', compact('patient', 'appointment'));
    }

    /**
     * Store vital signs (linked to medical record)
     */
    public function storeVitals(Request $request, Patient $patient, ?Appointment $appointment = null)
    {
        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'bp' => 'nullable|string|max:20',
            'hr' => 'nullable|string|max:20',
            'rr' => 'nullable|string|max:20',
            'temp' => 'nullable|string|max:20',
            'oxygen_saturation' => 'nullable|string|max:20',
            'weight' => 'nullable|string|max:20',
            'height' => 'nullable|string|max:20',
            'visit_date' => 'nullable|date',
        ]);

        $appointment = $appointment ?? ($request->appointment_id ? Appointment::find($request->appointment_id) : null);

        $record = MedicalRecord::create(array_merge($validated, [
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id ?? $appointment->doctor_id ?? auth()->user()->doctor->id ?? null,
            'appointment_id' => $appointment->id ?? $request->appointment_id ?? null,
            'visit_date' => $request->visit_date ?? ($appointment->appointment_date ?? now()),
            'reason_of_visit' => $validated['reason_of_visit'] ?? null,
        ]));

        return redirect()
            ->route('admin.records.show', $record->id)
            ->with('success', 'Vital signs and consultation details saved successfully.');
    }
}
