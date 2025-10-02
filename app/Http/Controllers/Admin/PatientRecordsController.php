<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\MedicalRecord;

class PatientRecordsController extends Controller
{
    public function index()
{
    $patients = Patient::with('user')->latest()->paginate(10);
    return view('admin.patients.index', compact('patients'));
}

    public function create()
    {
        // If you need dropdowns, load here.
        return view('admin.patients.create'); // <-- points to your full Blade form for new patient
    }
    public function store(Request $request)
    {
        $request->validate([
            // User fields
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',

            // Patient fields
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'address' => 'required|string',
            

            // Medical record fields
            'visit_date' => 'required|date',
            'reason_of_visit' => 'required|string|max:255',
            'history_present_illness' => 'nullable|string',
            'past_illnesses' => 'nullable|string',
            'past_surgeries' => 'nullable|string',
            'allergies' => 'nullable|string',
            'pre_conditions' => 'nullable|string',
            'family_history' => 'nullable|string',
            'childhood_vaccines' => 'nullable|string',
            'adult_vaccines' => 'nullable|string',
            'physical_exam' => 'nullable|string',
            'assessment' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'progress_notes' => 'nullable|string',
            'diagnostic_results' => 'nullable|string',

            // Vital signs
            'bp' => 'nullable|string|max:20',
            'hr' => 'nullable|integer',
            'rr' => 'nullable|integer',
            'temp' => 'nullable|numeric',
            'oxygen_saturation' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
        ]);

        // 1️⃣ Create User
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'patient',
        ]);

        // 2️⃣ Create Patient linked to User
        $patient = Patient::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'sex' => $request->sex,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        // 3️⃣ Create Medical Record including all fields & vitals
        $medicalRecord = MedicalRecord::create([
            'patient_id' => $patient->id,
            'appointment_id' => $request->appointment_id ?? null,
            'doctor_id' => auth()->user()->doctor->id ?? $request->doctor_id ?? null,
            'visit_date' => $request->visit_date,
            'reason_of_visit' => $request->reason_of_visit,
            'history_of_present_illness' => $request->history_present_illness,
            'past_illnesses' => $request->past_illnesses,
            'past_surgeries' => $request->past_surgeries,
            'allergies' => $request->allergies,
            'pre_conditions' => $request->pre_conditions,
            'family_history' => $request->family_history,
            'childhood_vaccines' => $request->childhood_vaccines,
            'adult_vaccines' => $request->adult_vaccines,
            'physical_exam' => $request->physical_exam,
            'assessment' => $request->assessment,
            'treatment_plan' => $request->treatment_plan,
            'current_medications' => $request->current_medications,
            'progress_notes' => $request->progress_notes,
            'diagnostic_results' => $request->diagnostic_results,
            'bp' => $request->bp,
            'hr' => $request->hr,
            'rr' => $request->rr,
            'temp' => $request->temp,
            'oxygen_saturation' => $request->oxygen_saturation,
            'weight' => $request->weight,
            'height' => $request->height,
            'bmi' => ($request->weight && $request->height) ? round($request->weight / (($request->height / 100) ** 2), 2) : null,
        ]);

        return redirect()->route('admin.patients.index')
                         ->with('success', 'Patient, medical record, and vitals saved successfully.');
    }
public function storeManual(Request $request)
{
    // Validate required fields
    $validated = $request->validate([
        'email'     => 'required|email|unique:users,email',
        'password'  => 'required|confirmed|min:6',
        'first_name'=> 'required',
        'last_name' => 'required',
        'birthdate' => 'required|date',
        'sex'       => 'required',
        'phone_number' => 'required',
        'address'   => 'required',
        'visit_date'=> 'required|date',
        'reason_of_visit' => 'required',
    ]);

    // Create user account
    $user = User::create([
        'email'    => $validated['email'],
        'password' => bcrypt($validated['password']),
          'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
        'role'     => 'patient',
    ]);

    // Create patient record
    $patient = Patient::create([
        'user_id'     => $user->id,
        'first_name'  => $validated['first_name'],
        'middle_name' => $request->middle_name,
        'last_name'   => $validated['last_name'],
        'birthdate'   => $validated['birthdate'],
        'sex'         => $validated['sex'],
        'phone_number'=> $validated['phone_number'],
        'address'     => $validated['address'],
    ]);

    // Create medical record
    MedicalRecord::create([
        'patient_id' => $patient->id,
        'visit_date' => $validated['visit_date'],
        'reason_of_visit' => $validated['reason_of_visit'],
        'history_present_illness' => $request->history_present_illness,
        'assessment' => $request->assessment,
        'treatment_plan' => $request->treatment_plan,
        'current_medications' => $request->current_medications,
        'progress_notes' => $request->progress_notes,
        'diagnostic_results' => $request->diagnostic_results,
        'bp' => $request->bp,
        'hr' => $request->hr,
        'rr' => $request->rr,
        'temp' => $request->temp,
        'oxygen_saturation' => $request->oxygen_saturation,
        'weight' => $request->weight,
        'height' => $request->height,
    ]);

    return redirect()->route('admin.patients.index')->with('success', 'Old patient record created successfully.');
}

}
