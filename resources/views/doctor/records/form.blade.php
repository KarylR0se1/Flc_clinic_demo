@extends('layouts.app')

@section('content')
<style>
  body {
      background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);
  }
</style>

<div class="container my-4">

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="recordTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="consultation-tab" data-bs-toggle="tab" data-bs-target="#consultation" type="button" role="tab">Consultation</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="laboratory-tab" data-bs-toggle="tab" data-bs-target="#laboratory" type="button" role="tab">Laboratory Request</button>
        </li>
    </ul>

    <div class="tab-content" id="recordTabsContent">

        <!-- Consultation Tab -->
        <div class="tab-pane fade show active" id="consultation" role="tabpanel">
            <form id="consultationForm" action="{{ route('doctor.records.update', $record->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <input type="hidden" name="appointment_id" value="{{ $record->appointment_id ?? '' }}">
                <input type="hidden" name="doctor_id" value="{{ $record->doctor_id ?? auth()->user()->doctor->id }}">

                <div class="bg-white shadow-sm p-4 border rounded-3">

                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">Medical Record</h3>
                        <p>{{ $patient->first_name }} {{ $patient->last_name }} | Visit Date: {{ \Carbon\Carbon::parse($record->visit_date ?? now())->format('F d, Y') }}</p>
                        <p>Attending Doctor: {{ auth()->user()->doctor->user->name ?? 'N/A' }}</p>
                    </div>

                    <!-- Vital Signs -->
                    <h5 class="fw-bold border-bottom pb-1 mb-3">1. Vital Signs</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Blood Pressure</label>
                            <input type="text" class="form-control" value="{{ $record->bp }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Heart Rate (bpm)</label>
                            <input type="number" class="form-control" value="{{ $record->hr }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Respiratory Rate</label>
                            <input type="number" class="form-control" value="{{ $record->rr }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Temperature (°C)</label>
                            <input type="number" class="form-control" value="{{ $record->temp }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Oxygen Saturation (%)</label>
                            <input type="number" class="form-control" value="{{ $record->oxygen_saturation }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" id="weight" class="form-control" value="{{ $record->weight }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" id="height" class="form-control" value="{{ $record->height }}" readonly>
                        </div>
                    </div>

                    <!-- Consultation Fields -->
                    <h5 class="fw-bold border-bottom pb-1 mb-3">2. Consultation</h5>
                    <div class="mb-3">
                        <label class="form-label">Reason of Visit</label>
                        <textarea name="reason_of_visit" class="form-control" rows="2">{{ old('reason_of_visit', $record->chief_complaint) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">History of Present Illness</label>
                        <textarea name="history_of_present_illness" class="form-control" rows="3">{{ old('history_of_present_illness', $record->history_of_present_illness) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Examination Findings</label>
                        <textarea name="examination" class="form-control" rows="3">{{ old('examination', $record->examination) }}</textarea>
                    </div>

                    <!-- Laboratory Results -->
                    <h5 class="fw-bold border-bottom pb-1 mb-3">3. Laboratory Results</h5>
                    @if($record->appointment?->laboratoryRequests?->count() > 0)
                        <ul class="list-group mb-3">
                            @foreach($record->appointment->laboratoryRequests as $lab)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $lab->test_type }} 
                                    @if($lab->result_file)
                                        <a href="{{ asset('storage/'.$lab->result_file) }}" target="_blank" class="btn btn-sm btn-primary">View Result</a>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No laboratory results linked to this appointment.</p>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Assessment / Diagnosis</label>
                        <textarea name="assessment" class="form-control" rows="2">{{ old('assessment', $record->assessment) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Treatment / Plan</label>
                        <textarea name="treatment_plan" class="form-control" rows="3">{{ old('treatment_plan', $record->treatment_plan) }}</textarea>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success px-5">Save Record</button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Laboratory Request Tab -->
        <div class="tab-pane fade" id="laboratory" role="tabpanel">
            <form id="laboratoryForm" action="{{ route('laboratory.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <input type="hidden" name="appointment_id" value="{{ $appointment?->id }}">
                <input type="hidden" name="medical_record_id" value="{{ $record?->id }}">

                <div class="bg-white shadow-sm p-4 border rounded-3">

                    <h5 class="fw-bold border-bottom pb-1 mb-3">Laboratory Request Form</h5>

                    <!-- Patient Info -->
                    <h6 class="fw-bold mt-3">Patient Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" value="{{ $patient->first_name }} {{ $patient->last_name }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" value="{{ $patient->address ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sex</label>
                            <input type="text" class="form-control" value="{{ $patient->sex ?? '' }}">
                        </div>
                    </div>

                    @php
                        $categories = [
                            'CHEMISTRY' => ['CBG','FBS','Cholesterol','Triglycerides','Blood Uric Acid','Creatinine','BUN','SGPT/ALT','SGOT/AST','Sodium','Potassium','Calcium','Troponin I'],
                            'HEMATOLOGY' => ['CBC, Platelet count','Blood typing','Clotting time','Bleeding time','Peripheral Smear'],
                            'SEROLOGY' => ['Typhidot','Dengue NS1','Dengue IgG/IgM','HBsAg','RPR'],
                            'CLINICAL MICROSCOPY' => ['Urinalysis','Pregnancy Test','Seminal Fluid Analysis'],
                            'PARASITOLOGY' => ['Fecalysis','Fecal Occult Blood'],
                            'MICROBIOLOGY' => ['KOH','AFS','Gram stain']
                        ];
                    @endphp

                    @foreach($categories as $title => $tests)
                        <h6 class="fw-bold mt-3">{{ $title }}</h6>
                        <div class="row mb-3">
                            @foreach($tests as $index => $test)
                                <div class="col-md-4 form-check">
                                    <input class="form-check-input" type="checkbox" name="{{ strtolower(str_replace(' ', '_', $title)) }}[]" value="{{ $test }}" id="{{ strtolower($title) }}_{{ $index }}">
                                    <label class="form-check-label" for="{{ strtolower($title) }}_{{ $index }}">{{ $test }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <h6 class="fw-bold mt-3">OTHERS</h6>
                    <input type="text" name="others" class="form-control mb-3" placeholder="Specify other tests">

                    <h6 class="fw-bold mt-3">Requested by (Physician)</h6>
                    <input type="text" name="requesting_physician" class="form-control mb-3" value="{{ auth()->user()->name }}">

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-save"></i> Submit Laboratory Request
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Catch all form submissions
    document.addEventListener('submit', function(e) {
        const form = e.target;

        if(form.id === 'consultationForm') {
            if(!confirm("Are you sure you want to save this medical record?")){
                e.preventDefault();
            }
        }

        if(form.id === 'laboratoryForm') {
            if(!confirm("Are you sure you want to submit this laboratory request?")){
                e.preventDefault();
            }
        }
    });
});
</script>
@endsection
