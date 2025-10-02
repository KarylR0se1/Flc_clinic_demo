@extends('layouts.app')
@section('content')
<style>
  body {
      background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);
  }
  /* Doctor Cards */
  .doctor-card {
      transition: transform 0.25s ease, box-shadow 0.25s ease;
      border-radius: 1rem;
  }
  .doctor-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }
  .doctor-icon {
      font-size: 2.5rem;
      color: #3594ed;
      margin-bottom: 0.5rem;
  }
  /* Specialization */
  .doctor-specialization {
      display: inline-block;
      font-size: 1rem;
      font-weight: 600;
      background: linear-gradient(90deg, #ffcc00, #ff9900);
      color: #1a1a1a;
      padding: 4px 12px;
      border-radius: 20px;
      letter-spacing: 0.5px;
      text-shadow: none;
  }
  /* Appointments Table */
  .table thead {
      background: #3594ed;
      color: #fff;
  }
  .badge {
      padding: 0.4em 0.6em;
      font-size: 0.8rem;
      border-radius: 0.5rem;
  }
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-body bg-primary text-white rounded-top">
            <h4 class="fw-bold mb-1"><i class="bi bi-people-fill me-2"></i> Doctors & Appointments</h4>
            <p class="mb-0 small">Manage and monitor patient appointments by doctor</p>
        </div>
    </div>

    <div class="row">
        @foreach($doctors as $doctor)
            <!-- Doctor Card -->
            <div class="col-md-4 mb-4 doctor-wrapper" id="doctor-wrapper-{{ $doctor->id }}">
                <div class="card doctor-card h-100 text-center shadow-sm border-0 p-3">
                    <div class="doctor-icon"><i class="bi bi-person-badge"></i></div>
                    <h5 class="fw-bold text-dark mb-1">Dr. {{ $doctor->user->name ?? 'N/A' }}</h5>
                    <p>
                        <span class="doctor-specialization">{{ $doctor->specialization ?? 'N/A' }}</span>
                    </p>
                    <button class="btn btn-primary btn-sm fw-bold rounded-pill px-3 mt-auto" 
                            type="button" onclick="showAppointments({{ $doctor->id }})">
                        View Appointments
                    </button>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="col-12 mb-4 d-none" id="appointments-{{ $doctor->id }}">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-calendar-check me-2"></i> Appointments for Dr. {{ $doctor->user->name ?? 'N/A' }}
                        </h5>

                        <!-- Appointments Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($doctor->appointments as $index => $appointment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $appointment->patient?->user?->name ?? $appointment->patient?->first_name.' '.$appointment->patient?->last_name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y') : 'N/A' }}</td>
                                            <td>{{ $appointment->appointment_time ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($appointment->status == 'pending') bg-warning text-dark
                                                    @elseif($appointment->status == 'approved') bg-success
                                                    @else bg-danger @endif">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($appointment->status == 'pending')
                                                    <a href="{{ route('admin.appointments.updateStatus', [$appointment->id, 'approved']) }}" 
                                                       class="btn btn-sm btn-success fw-bold mb-1">Approve</a>
                                                    <a href="{{ route('admin.appointments.updateStatus', [$appointment->id, 'rejected']) }}" 
                                                       class="btn btn-sm btn-danger fw-bold mb-1">Reject</a>
                                                @else
                                                    @if($appointment->patient)
                                                        @if($appointment->status == 'approved')
                                                            <a href="{{ route('admin.vitals.create', [$appointment->patient->id, $appointment->id]) }}" 
                                                               class="btn btn-sm btn-outline-primary fw-bold">+ Vitals</a>
                                                        @else
                                                            <button class="btn btn-sm btn-outline-secondary fw-bold" disabled>+ Vitals</button>
                                                        @endif
                                                    @else
                                                        <span class="text-muted small">No patient linked</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-muted">No appointments found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Back button -->
                        <div class="text-end mt-3">
                            <button class="btn btn-outline-secondary btn-sm fw-bold rounded-pill px-3" 
                                    onclick="hideAppointments({{ $doctor->id }})">
                                Back
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<script>
function showAppointments(doctorId) {
    document.querySelectorAll('.doctor-wrapper').forEach(el => el.classList.add('d-none'));
    document.getElementById('appointments-' + doctorId).classList.remove('d-none');
}
function hideAppointments(doctorId) {
    document.getElementById('appointments-' + doctorId).classList.add('d-none');
    document.querySelectorAll('.doctor-wrapper').forEach(el => el.classList.remove('d-none'));
}
</script>
@endsection
