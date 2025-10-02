{{-- resources/views/admin/appointments/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary mb-3">Appointment Details</h2>

    <div class="card p-3 shadow-sm">
        <p><strong>Patient:</strong> {{ $appointment->patient->user->name ?? '-' }}</p>
        <p><strong>Doctor:</strong> Dr. {{ $appointment->doctor->user->name ?? '-' }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</p>
        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
        <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($appointment->status) }}</span></p>
    </div>
</div>
@endsection
