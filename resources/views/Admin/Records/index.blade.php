@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}

</style>
<div class="container py-4">

    <!-- Patient Info -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-start">
            <div>
                <h4 class="fw-bold mb-3 text-uppercase text-primary">Patient Medical Records</h4>
                <p class="mb-1"><strong>Patient Name:</strong> {{ $patient->first_name }} {{ $patient->last_name }}</p>
                <p class="mb-1"><strong>Sex:</strong> {{ ucfirst($patient->sex) }} | 
                   <strong>Birthdate:</strong> {{ $patient->birthdate ?? 'N/A' }}</p>
                <p class="mb-0"><strong>Contact:</strong> {{ $patient->phone_number ?? 'N/A' }}</p>
            </div>

            <!-- Add Record Button -->
            <div class="text-end">
                <a href="{{ route('admin.records.create', $patient->id) }}" 
                   class="btn btn-primary">
                    <i class="bi bi-file-earmark-plus"></i> Add Record
                </a>
            </div>
        </div>
    </div>

    <!-- Records Table -->
    <div class="card border-0 shadow-sm">
        
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-primary text-dark text-center">
                    <tr>
                        <th style="width: 120px;">Visit Date</th>
                        <th>Reason of Visit</th>
                        <th style="width: 200px;">Attending Doctor</th>
                        <th style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                   @forelse($patient->medicalRecords()->orderByDesc('visit_date')->orderByDesc('created_at')->get() as $record)

                        <tr>
                            <td class="text-center">
                                @if($record->appointment && $record->appointment->appointment_date)
                                    {{ \Carbon\Carbon::parse($record->appointment->appointment_date)->format('F j, Y') }}
                                @elseif($record->visit_date)
                                    {{ \Carbon\Carbon::parse($record->visit_date)->format('F j, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>

                            <td>{{ Str::limit($record->chief_complaint ?? 'N/A', 50) }}</td>
                            <td>{{ $record->doctor?->user->name ?? 'Not Assigned' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.records.show', $record->id) }}" 
                                   class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('admin.records.print', $record->id) }}" target="_blank" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-printer"></i> Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                <em>No past records found for this patient.</em>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
