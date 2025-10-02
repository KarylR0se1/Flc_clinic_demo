@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}

</style>
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-primary">
            <i class="bi bi-flask"></i> Laboratory Requests
        </h2>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Requests Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Patient</th>
                            <th>Test Type</th>
                            <th>Request Date</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th style="width: 250px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $lab)
                            <tr>
                                <td class="fw-semibold">{{ $lab->patient->first_name ?? 'N/A' }} {{ $lab->patient->last_name ?? '' }}</td>
                                <td>{{ $lab->test_type }}</td>
                                <td>{{ \Carbon\Carbon::parse($lab->created_at)->format('M d, Y') }}</td>
                                <td>{{ $lab->requested_by }}</td>
                                <td class="text-center">
                                    @if(strtolower($lab->status) === 'complete')
                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i> Completed
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                            <i class="bi bi-hourglass-split me-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- If not complete, show attach button -->
                                    @if(strtolower($lab->status) !== 'complete')
                                        <button class="btn btn-sm btn-primary mb-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#attachResultModal{{ $lab->id }}">
                                            <i class="bi bi-upload"></i> Attach Result
                                        </button>

                                        <!-- Modal for attaching result -->
                                        <div class="modal fade" id="attachResultModal{{ $lab->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow-lg">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-upload me-2"></i> Attach Laboratory Result
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('laboratory.attachResult', $lab->id) }}" 
                                                              method="POST" 
                                                              enctype="multipart/form-data">
                                                            @csrf

                                                            <!-- Appointment Selection -->
                                                            <div class="mb-3">
                                                                <label class="form-label">Link to Appointment</label>
                                                                <select name="appointment_id" class="form-select" required>
                                                                    <option value="">-- Select Appointment --</option>

                                                                    @if($lab->patient->appointments()->where('status', 'approved')->latest()->first())
                                                                        @php $currentAppt = $lab->patient->appointments()->where('status','approved')->latest()->first(); @endphp
                                                                        <option value="{{ $currentAppt->id }}">
                                                                            Current: {{ $currentAppt->appointment_date->format('M d, Y') }} 
                                                                            with Dr. {{ $currentAppt->doctor->user->name }}
                                                                        </option>
                                                                    @endif

                                                                    @foreach($lab->patient->appointments as $appointment)
                                                                        <option value="{{ $appointment->id }}">
                                                                            {{ $appointment->appointment_date->format('M d, Y') }} 
                                                                            with Dr. {{ $appointment->doctor->user->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- File Upload -->
                                                            <div class="mb-3">
                                                                <label class="form-label">Result File</label>
                                                                <input type="file" name="result_file" 
                                                                       class="form-control" 
                                                                        accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" required>
                                                            </div>

                                                            <button type="submit" class="btn btn-success w-100">
                                                                <i class="bi bi-check2-circle"></i> Upload Result
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($lab->result_file)
                                        <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#resultModal"
                                                data-file="{{ asset('storage/'.$lab->result_file) }}">
                                            <i class="bi bi-eye"></i> View Result
                                        </button>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No laboratory requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing result -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-pdf me-2"></i> Laboratory Result
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="height:80vh;">
                <iframe id="resultFrame" src="" width="100%" height="100%" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Script to load PDF in modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const resultModal = document.getElementById('resultModal');
    const resultFrame = document.getElementById('resultFrame');

    resultModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        resultFrame.src = button.getAttribute('data-file');
    });

    resultModal.addEventListener('hidden.bs.modal', function () {
        resultFrame.src = "";
    });
});
</script>
@endsection
