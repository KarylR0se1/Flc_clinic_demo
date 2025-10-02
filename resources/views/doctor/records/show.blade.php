@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
</style>

<div class="container py-4">

    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary text-uppercase">Medical Record</h2>
        <p class="mb-0"><strong>Patient:</strong> {{ $record->patient->first_name }} {{ $record->patient->last_name }}</p>
        <p class="mb-0"><strong>Date of Visit:</strong> 
            @php
                $visitDate = $record->appointment?->appointment_date ?? $record->visit_date;
            @endphp
            {{ $visitDate ? \Carbon\Carbon::parse($visitDate)->format('l, F d, Y') : 'N/A' }}
        </p>
    </div>

    <div class="border rounded-3 p-4 bg-white shadow-sm">

        <!-- Visit Information -->
        <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">Visit Information</h5>
        <p><strong>Attending Doctor:</strong> {{ $record->doctor?->user->name ?? 'Not yet assigned' }}</p>


        <!-- Vital Signs -->
        <h5 class="fw-bold text-primary border-bottom pb-2 mt-4 mb-3">Vital Signs</h5>
        <table class="table table-bordered align-middle">
            <tbody>
                <tr>
                    <th class="bg-light">Blood Pressure</th><td>{{ $record->bp ?? 'N/A' }}</td>
                    <th class="bg-light">Heart Rate</th><td>{{ $record->hr ?? 'N/A' }}</td>
                    <th class="bg-light">Temperature</th><td>{{ $record->temp ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Weight</th><td>{{ $record->weight ?? 'N/A' }}</td>
                    <th class="bg-light">Height</th><td>{{ $record->height ?? 'N/A' }}</td>
                   
                <tr>
                    <th class="bg-light">Respiratory Rate</th><td colspan="5">{{ $record->rr ?? 'N/A' }}</td>
                </tr>

            </tbody>
        </table>
<!-- Consultation Fields -->
<h5 class="fw-bold text-primary border-bottom pb-2 mt-4 mb-3">Consultation</h5>
<p><strong>Reason of Visit:</strong> {{ $record->reason_of_visit ?? $record->chief_complaint ?? 'N/A' }}</p>
<p><strong>History of Present Illness:</strong> {{ $record->history_of_present_illness ?? 'N/A' }}</p>
<p><strong>Examination Findings:</strong> {{ $record->examination ?? 'N/A' }}</p>
<p><strong>Assessment / Diagnosis:</strong> {{ $record->assessment ?? 'N/A' }}</p>
<p><strong>Treatment / Plan:</strong> {{ $record->treatment_plan ?? 'N/A' }}</p>

        <!-- Laboratory Results -->
        <h5 class="fw-bold text-primary border-bottom pb-2 mt-4 mb-3">Laboratory Results</h5>
        @if($record->laboratoryRequests && $record->laboratoryRequests->count() > 0)
            <table class="table table-hover table-striped align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Test Type</th>
                        <th>Requested By</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->laboratoryRequests as $lab)
                        <tr>
                            <td>{{ $lab->test_type }}</td>
                            <td>{{ $lab->requested_by }}</td>
                            <td>{{ \Carbon\Carbon::parse($lab->created_at)->format('F d, Y') }}</td>
                            <td class="text-center">
                                @if($lab->status === 'complete')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lab->status === 'complete' && $lab->result_file)
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#resultModal" 
                                            data-file="{{ asset('storage/' . $lab->result_file) }}">
                                        View Result
                                    </button>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-muted"><em>No laboratory results attached to this record.</em></p>
        @endif
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Laboratory Result</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height:80vh;">
                <iframe id="resultFrame" src="" width="100%" height="100%" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var resultModal = document.getElementById('resultModal');
    var resultFrame = document.getElementById('resultFrame');

    resultModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var fileUrl = button.getAttribute('data-file');
        resultFrame.src = fileUrl;
    });

    resultModal.addEventListener('hidden.bs.modal', function () {
        resultFrame.src = "";
    });
});
</script>
@endsection
