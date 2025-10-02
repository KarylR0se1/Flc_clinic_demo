@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
</style>

<div class="container mt-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 fw-bold">Add Vital Signs</h4>
        </div>

        <div class="card-body">
            <form id="vitalsForm" action="{{ route('admin.vitals.store', [$patient->id, $appointment->id ?? '']) }}" method="POST">
                @csrf

                <!-- Hidden fields -->
                <input type="hidden" name="appointment_id" value="{{ old('appointment_id', $appointment->id ?? $appointment_id ?? '') }}">
                <input type="hidden" name="patient_id" value="{{ old('patient_id', $patient->id ?? '') }}">

                <!-- Appointment Info -->
                <div class="row mb-3">
                   <div class="col-md-6">
                        <label class="fw-semibold">Appointment Date</label>
                        <input type="text" class="form-control" 
                            value="{{ old('appointment_date', isset($appointment->appointment_date) ? \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') : 'N/A') }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold">Attending Doctor</label>
                        <input type="text" class="form-control" 
                               value="{{ old('doctor_name', $appointment->doctor?->user?->name ?? 'Not Assigned') }}" readonly>
                    </div>
                </div>

                <!-- Vital Signs -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-semibold">Blood Pressure</label>
                        <input type="text" name="bp" class="form-control" placeholder="e.g. 120/80" value="{{ old('bp') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-semibold">Heart Rate (bpm)</label>
                        <input type="number" name="hr" class="form-control" placeholder="e.g. 72" value="{{ old('hr') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-semibold">Respiratory Rate (breaths/min)</label>
                        <input type="number" name="rr" class="form-control" placeholder="e.g. 16" value="{{ old('rr') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-semibold">Temperature (°C)</label>
                        <input type="number" step="0.1" name="temp" class="form-control" placeholder="e.g. 37.0" value="{{ old('temp') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-semibold">Oxygen Saturation (%)</label>
                        <input type="number" name="oxygen_saturation" class="form-control" placeholder="e.g. 98" value="{{ old('oxygen_saturation') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-semibold">Weight (kg)</label>
                        <input type="number" step="0.1" name="weight" class="form-control" placeholder="e.g. 70.5" value="{{ old('weight') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="fw-semibold">Height (cm)</label>
                        <input type="number" step="0.1" name="height" class="form-control" placeholder="e.g. 170" value="{{ old('height') }}">
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark px-4">
                        <i class="bi bi-save"></i> Save Vital Signs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('vitalsForm');

    form.addEventListener('submit', function(e) {
        const confirmed = confirm("Are you sure you want to save these vital signs?");
        if (!confirmed) {
            e.preventDefault(); // Stop submission if user cancels
        }
    });
});
</script>
@endsection
