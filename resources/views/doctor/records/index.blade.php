@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
</style>

<div class="container-fluid px-4 py-3">

    <!-- Page Header -->
    <div class="card shadow mb-4">
        <div class="card-body text-center bg-dark text-white rounded-top">
            <h2 class="mb-0 fw-bold">📋 Forwarded Medical Records</h2>
        </div>
    </div>

    <!-- Records Table -->
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Reason of Visit</th>
                        <th>Attending Doctor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr class="text-center">
                            <td>
                                @if($record->appointment?->appointment_date)
                                    {{ \Carbon\Carbon::parse($record->appointment->appointment_date)->format('F j, Y') }}
                                @elseif($record->visit_date)
                                    {{ \Carbon\Carbon::parse($record->visit_date)->format('F j, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-start">{{ $record->patient->first_name }} {{ $record->patient->last_name }}</td>
                            <td class="text-start">{{ \Illuminate\Support\Str::limit($record->chief_complaint, 40) }}</td>
                            <td>{{ $record->doctor?->user?->name ?? 'Not Assigned' }}</td>
                            <td>
                                <a href="{{ route('doctor.records.show', $record->id) }}" class="btn btn-sm btn-success">
                                    Add Diagnosis
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No records forwarded</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
