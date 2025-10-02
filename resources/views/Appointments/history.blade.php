@extends('layouts.app')

@section('content')
<style>
  body {
      background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);
  }
</style>

<div class="container mt-4">
    <h3 class="fw-bold text-dark mb-3">Appointment History</h3>

    {{-- Folder-style Tabs --}}
    <ul class="nav nav-tabs folder-tabs mb-3 justify-content-start">
        @php
            $tabs = [
                'all' => 'info',
                'pending' => 'warning',
                'approved' => 'success',
                'cancelled' => 'danger',
                'rejected' => 'secondary',
            ];
        @endphp
        @foreach($tabs as $tab => $color)
            <li class="nav-item flex-fill flex-md-grow-0">
                <a class="nav-link text-center
                    {{ $status == $tab 
                        ? 'active bg-' . $color . ' text-white' 
                        : 'bg-light text-dark' }}" 
                   href="{{ route('appointments.history', ['status' => $tab]) }}">
                   {{ ucfirst($tab) }}
                </a>
            </li>
        @endforeach
    </ul>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            @if($appointments->isEmpty())
                <p class="text-muted">No {{ ucfirst($status) }} appointments found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle d-none d-md-table">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr 
                                    @if($appointment->status == 'canceled') class="table-danger" 
                                    @elseif($appointment->status == 'rejected') class="table-secondary" 
                                    @elseif($appointment->status == 'approved') class="table-success" 
                                    @elseif($appointment->status == 'pending') class="table-warning" 
                                    @endif
                                >
                                    <td>{{ $appointment->doctor->specialization ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i a') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $tabs[$appointment->status] ?? 'info' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(in_array($appointment->status, ['pending','accepted']))
                                            <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="d-inline-flex gap-1 flex-wrap">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger hover-scale">
                                                    Cancel
                                                </button>
                                                <a href="{{ route('appointments.reschedule', $appointment->id) }}" 
                                                class="btn btn-sm btn-primary hover-scale">
                                                    Reschedule
                                                </a>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Mobile card layout --}}
                    <div class="d-md-none">
                        @foreach($appointments as $appointment)
                            <div class="border rounded p-3 mb-3 
                                @if($appointment->status == 'canceled') bg-danger bg-opacity-10 
                                @elseif($appointment->status == 'rejected') bg-secondary bg-opacity-10 
                                @elseif($appointment->status == 'approved') bg-success bg-opacity-10 
                                @elseif($appointment->status == 'pending') bg-warning bg-opacity-10 
                                @endif">
                                <div><strong>Service:</strong> {{ $appointment->doctor->specialization ?? 'N/A' }}</div>
                                <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</div>
                                <div><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i a') }}</div>
                                <div>
                                    <strong>Status:</strong> 
                                    <span class="badge bg-{{ $tabs[$appointment->status] ?? 'info' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    @if(in_array($appointment->status, ['pending','accepted']))
                                        <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="d-flex flex-wrap gap-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger w-100">
                                                Cancel
                                            </button>
                                            <a href="{{ route('appointments.reschedule', $appointment->id) }}" class="btn btn-sm btn-primary w-100">
                                                Reschedule
                                            </a>
                                        </form>
                                    @else
                                        <span class="text-muted">No actions</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Styles --}}
@push('styles')
<style>
    .hover-scale:hover {
        transform: scale(1.05);
        transition: 0.2s ease-in-out;
    }

    .folder-tabs {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .folder-tabs .nav-link {
        border-radius: 12px 12px 0 0;
        padding: 8px 14px;
        font-weight: 500;
        border: none;
        box-shadow: inset 0 -2px 0 rgba(0,0,0,0.1);
        transition: all 0.2s ease-in-out;
    }

    .folder-tabs .nav-link:hover {
        transform: translateY(-2px);
    }

    .folder-tabs .nav-link.active {
        box-shadow: 0 -3px 10px rgba(0,0,0,0.15);
        position: relative;
        top: 2px;
        border-bottom: none !important;
        z-index: 2;
    }

    .card {
        border-radius: 0 12px 12px 12px;
    }
</style>
@endpush
@endsection
