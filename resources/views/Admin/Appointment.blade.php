
@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}

</style>
<div class="container mt-4">
    <h2>Appointment History</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($appointments as $index => $appointment)
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $appointment->patient->name }}</td>
                <td>{{ $appointment->doctor->name }}</td>
                <td>{{ $appointment->appointment_date }}</td>
                <td>
                    @if($appointment->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($appointment->status == 'accepted')
                        <span class="badge bg-success">Accepted</span>
                    @else
                        <span class="badge bg-danger">Declined</span>
                    @endif
                </td>
                <td>
                    @if($appointment->status == 'pending')
                        <a href="{{ route('admin.appointments.updateStatus', [$appointment->id, 'accepted']) }}" class="btn btn-success btn-sm">Accept</a>
                        <a href="{{ route('admin.appointments.updateStatus', [$appointment->id, 'declined']) }}" class="btn btn-danger btn-sm">Decline</a>
                    @else
                        <em>No action</em>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">No appointments found</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<form action="{{ route('admin.appointments.sendReminder', $appointment->id) }}" method="POST">
    @csrf
    <button class="btn btn-warning">Send Reminder</button>
</form>

@endsection
