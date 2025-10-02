@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
</style>

<div class="container my-4">
    <div class="card shadow-sm p-4">
        <h2 class="text-center fw-bold mb-4">Medical Record Form</h2>

        <form id="multiStepForm" action="{{ route('admin.records.store', $patient->id) }}" method="POST">
            @csrf

            <!-- ==================== STEP 1: Patient Info & History ==================== -->
            <div class="form-step active">
                <h3 class="fw-bold text-dark mb-3">Patient Information</h3>

                <!-- Display Patient Info -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" class="form-control" value="{{ $patient->first_name }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Middle Name</label>
                        <input type="text" class="form-control" value="{{ $patient->middle_name }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" class="form-control" value="{{ $patient->last_name }}" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Birthdate</label>
                        <input type="text" class="form-control" value="{{ optional($patient->birthdate)->format('Y-m-d') }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Sex</label>
                        <input type="text" class="form-control" value="{{ $patient->sex }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Contact</label>
                        <input type="text" class="form-control" value="{{ $patient->phone_number }}" disabled>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea class="form-control" rows="2" disabled>{{ $patient->address }}</textarea>
                </div>

                <!-- Visit Date -->
                <div class="mb-3">
                    <label for="visit_date" class="form-label">Visit Date</label>
                    <input type="date" name="visit_date" id="visit_date" 
                        class="form-control" value="{{ old('visit_date', now()->toDateString()) }}" required>
                </div>

                <!-- Attending Doctor Selection -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Attending Doctor</label>
                    <select name="doctor_id" class="form-select" required>
                        <option value="">-- Select Doctor --</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->user->name }} ({{ $doctor->specialization ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Medical History -->
                <h4 class="fw-bold border-bottom pb-2">Medical History</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Past Illnesses</label>
                        <textarea name="past_illnesses" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Past Surgeries</label>
                        <textarea name="past_surgeries" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pre-existing Conditions</label>
                        <textarea name="pre_conditions" rows="2" class="form-control"></textarea>
                    </div>
                </div>

                <!-- Family History -->
                <h4 class="fw-bold border-bottom pb-2">Family Medical History</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="form-label d-block">Check if applicable:</label>
                        @php
                            $familyHistory = ['hypertension','diabetes','heart_disease','cancer','tb'];
                        @endphp
                        @foreach($familyHistory as $fh)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="family_{{ $fh }}" value="1">
                            <label class="form-check-label">{{ ucfirst(str_replace('_',' ',$fh)) }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Immunizations -->
                <h4 class="fw-bold border-bottom pb-2">Immunization Records</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Childhood Vaccinations</label>
                        <textarea name="childhood_vaccines" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Adult Vaccinations</label>
                        <textarea name="adult_vaccines" rows="2" class="form-control"></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-primary next-step">Next </button>
                </div>
            </div>

            <!-- ==================== STEP 2: Clinical Records ==================== -->
            <div class="form-step d-none">
                <h3 class="fw-bold text-black mt-3 mb-3">Clinical Records</h3>

                <!-- Medications -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Current Medications</label>
                        <textarea name="current_medications" rows="2" class="form-control"></textarea>
                    </div>
                </div>

                <!-- Treatment History -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Treatment History</label>
                    <textarea name="treatment_history" rows="3" class="form-control"></textarea>
                </div>

                <!-- Progress Notes -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Progress Notes</label>
                    <textarea name="progress_notes" rows="3" class="form-control"></textarea>
                </div>

                <!-- Lab / Diagnostic -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Lab Results</label>
                        <textarea name="lab_results" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Diagnostic Results</label>
                        <textarea name="diagnostic_results" rows="2" class="form-control"></textarea>
                    </div>
                </div>

                <!-- Vital Signs -->
                <h4 class="fw-bold border-bottom pb-2">Vital Signs & Physical Exam</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Blood Pressure</label>
                        <input type="text" name="bp" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Heart Rate</label>
                        <input type="text" name="hr" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Temperature</label>
                        <input type="text" name="temp" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Respiratory Rate</label>
                        <input type="text" name="rr" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Height</label>
                        <input type="text" name="height" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Weight</label>
                        <input type="text" name="weight" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">BMI</label>
                        <input type="text" name="bmi" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Physical Exam</label>
                        <textarea name="physical_exam" rows="2" class="form-control"></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prev-step">Back</button>
                    <button type="submit" class="btn btn-success px-4">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const steps = document.querySelectorAll(".form-step");
    let currentStep = 0;

    // Next buttons
    document.querySelectorAll(".next-step").forEach(btn => {
        btn.addEventListener("click", () => {
            // Validate required fields in current step
            const requiredFields = steps[currentStep].querySelectorAll("[required]");
            let valid = true;
            requiredFields.forEach(f => {
                if (!f.value) {
                    f.classList.add("is-invalid");
                    valid = false;
                } else {
                    f.classList.remove("is-invalid");
                }
            });
            if (!valid) return;

            steps[currentStep].classList.add("d-none");
            currentStep++;
            steps[currentStep].classList.remove("d-none");
        });
    });

    // Previous buttons
    document.querySelectorAll(".prev-step").forEach(btn => {
        btn.addEventListener("click", () => {
            steps[currentStep].classList.add("d-none");
            currentStep--;
            steps[currentStep].classList.remove("d-none");
        });
    });

    // Confirmation on submit
    const form = document.getElementById("multiStepForm");
    form.addEventListener("submit", function(e) {
        const confirmed = confirm("Are you sure you want to save this medical record?");
        if (!confirmed) e.preventDefault();
    });
});

</script>
@endsection
