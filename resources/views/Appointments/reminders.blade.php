@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3 text-primary">Upcoming Appointments</h2>

    @php
        // Filter only approved appointments
        $approvedAppointments = $appointments->filter(fn($a) => $a->status === 'approved');
    @endphp

    @if($approvedAppointments->isEmpty())
        <div class="alert alert-info">
            No approved appointments for tomorrow.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        @if(auth()->user()->role === 'admin')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvedAppointments as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->user->name ?? '-' }}</td>
                            <td>Dr. {{ $appointment->doctor->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                            <td>
                                <span class="badge bg-success">Approved</span>
                            </td>
                            @if(auth()->user()->role === 'admin')
                                <td>
                                    <form action="{{ route('appointments.sendReminder', $appointment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Send Reminder
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
