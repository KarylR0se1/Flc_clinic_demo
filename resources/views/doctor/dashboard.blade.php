@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
</style>

<div class="container ">

    <!-- Doctor Header -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body text-center bg-primary text-white rounded-top">
            <h3 class="fw-bold mb-1">{{ $doctor->specialization ?? 'Specialization' }}</h3>
            <p class="mb-0">Dr. {{ $doctor->user->name ?? 'Unknown' }}</p>
        </div>
    </div>

    <!-- Appointment List -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">List of Appointments</h5>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->user->name ?? $appointment->patient->first_name . ' ' . $appointment->patient->last_name ?? 'Unknown' }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i a') }}</td>
                            <td>{{ ucfirst($appointment->status) ?? 'Pending' }}</td>
                            <td>
                                @php
                                    $record = $appointment->medicalRecord ?? null; // Ensure relationship exists
                                @endphp
                                @if($record)
                                    <a href="{{ route('doctor.records.form', $record->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                @else
                                    <span class="text-muted">No record yet</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
