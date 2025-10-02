@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Appointment Details</h3>

    <ul class="list-group">
        <li class="list-group-item"><strong>Patient:</strong> {{ $appointment->patient->user->name ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Doctor:</strong> {{ $appointment->doctor->user->name ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</li>
        <li class="list-group-item"><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</li>
        <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($appointment->status) }}</li>
        <li class="list-group-item"><strong>Created At:</strong> {{ $appointment->created_at->format('M d, Y h:i A') }}</li>
    </ul>

    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary mt-3">Back to Appointments</a>
</div>
@endsection
