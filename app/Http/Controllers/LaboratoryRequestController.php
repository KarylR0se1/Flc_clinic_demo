<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaboratoryRequest;
use App\Models\Doctor; // Assuming there's a Doctor model
use Illuminate\Support\Facades\Auth;

class LaboratoryRequestController extends Controller
{
    // Doctor view: Display lab requests for the logged-in doctor
    

    // Admin view: Display all lab requests
    public function adminIndex($patientId, $appointmentId = null)
    {
         $query = \App\Models\LaboratoryRequest::with(['patient', 'appointment', 'medicalRecord'])
        ->where('patient_id', $patientId);

    if ($appointmentId) {
        $query->where('appointment_id', $appointmentId);
    }

    $requests = $query->orderByDesc('created_at')->get();

    return view('admin.laboratory.index', compact('requests'));
    }

    // Show the form to create a new laboratory request
     public function create(Patient $patient)
    {
        // Get the next upcoming approved appointment for this patient
        $appointment = Appointment::where('patient_id', $patient->id)
            ->where('status', 'approved')
            ->whereDate('appointment_date', '>=', now())
            ->orderBy('appointment_date', 'asc')
            ->first();

        return view('patients.laboratory_request', compact('patient', 'appointment'));
    }

    // Submit the new laboratory request
  public function store(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'appointment_id' => 'nullable|exists:appointments,id',
        'medical_record_id' => 'nullable|exists:medical_records,id', // NEW
        'patient_name' => 'required|string',
        'address' => 'nullable|string',
        'request_date' => 'required|date',
        'age_sex' => 'nullable|string',
        'diagnosis' => 'nullable|string',
        'chemistry' => 'nullable|array',
        'hematology' => 'nullable|array',
        'serology' => 'nullable|array',
        'clinical_microscopy' => 'nullable|array',
        'parasitology' => 'nullable|array',
        'microbiology' => 'nullable|array',
        'others' => 'nullable|string',
        'requesting_physician' => 'nullable|string',
    ]);

    // Combine all selected tests into a single test_type string (optional)
    $testTypes = [];
    foreach (['chemistry','hematology','serology','clinical_microscopy','parasitology','microbiology'] as $category) {
        if(!empty($validated[$category])) {
            $testTypes = array_merge($testTypes, $validated[$category]);
        }
    }
    if(!empty($validated['others'])) {
        $testTypes[] = $validated['others'];
    }

    // Create new laboratory request record
    $labRequest = \App\Models\LaboratoryRequest::create([
        'patient_id' => $validated['patient_id'],
        'appointment_id' => $validated['appointment_id'] ?? null,
        'medical_record_id' => $validated['medical_record_id'] ?? null,
        'patient_name' => $request->patient_name ?? $request->patient_id, // fixed '=>'
        'test_type' => !empty($testTypes) ? implode(', ', $testTypes) : 'Unknown Test',
        'requested_by' => $validated['requesting_physician'] ?? auth()->user()->name,
        'status' => 'pending',
        'request_date' => $request->request_date ?? now(),
    ]);

    // Redirect back with success message
    return redirect()->back()->with('success', 'Laboratory request submitted successfully!');
}

public function show($id)
{
    $request = LaboratoryRequest::with(['patient', 'appointment'])->findOrFail($id);

    return view('laboratory.show', compact('request'));
}
public function index()
{
    // Get all laboratory requests with patient and appointment info
    $requests = LaboratoryRequest::with(['patient', 'appointment.doctor.user'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('laboratory.index', compact('requests'));
}
public function update(Request $request, $id)
{
    $labRequest = LaboratoryRequest::findOrFail($id);

    $labRequest->results = $request->only([
        'chemistry',
        'hematology',
        'serology',
        'clinical_microscopy',
        'parasitology',
        'microbiology',
        'others'
    ]);

    $labRequest->status = 'Completed';
    $labRequest->save();

    return redirect()->route('laboratory.show', $labRequest->id)
                     ->with('success', 'Laboratory results attached successfully.');
}
public function attachResult(Request $request, $id)
{
    // 1. Validate upload
    $request->validate([
        'result_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    // 2. Find the lab request
    $lab = LaboratoryRequest::findOrFail($id);

    // 3. Store uploaded file
    $filePath = $request->file('result_file')->store('lab_results', 'public');

    // 4. Update the lab request
    $lab->update([
        'result_file' => $filePath,
        'status' => 'complete',
    ]);

    // 5. If lab is not linked to a medical record, create one
    if (!$lab->medical_record_id) {
        $medicalRecord = \App\Models\MedicalRecord::create([
            'patient_id'       => $lab->patient_id,
            'appointment_id'   => $lab->appointment_id,
            'visit_date'       => now(),
            'reason_of_visit'  => 'Laboratory Test Result',
        ]);

        $lab->update(['medical_record_id' => $medicalRecord->id]);
    }

    // 6. (Optional) Save file path in MedicalRecord if you want direct reference
    if ($lab->medicalRecord) {
        $lab->medicalRecord->update([
            'lab_results' => $filePath
        ]);
    }

    return redirect()->back()->with('success', 'Result attached and linked to medical record successfully.');
}


}
