@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);
    }

    .card {
        border-radius: 0.75rem;
    }

    .nav-tabs .nav-link {
        border-radius: 0.5rem;
    }

    @media (max-width: 768px) {
        .nav-tabs .nav-item {
            flex: 1 1 45%;
        }

        .card .card-body {
            padding: 1rem;
        }

        .table th, .table td {
            font-size: 0.875rem;
        }
    }
</style>
@php
    use Carbon\Carbon;

    $appointments = $appointments ?? collect();
    $currentYear = Carbon::now()->year;

    // Prepare all 12 months abbreviated with year-month for grouping
    $months = collect(range(1, 12))->mapWithKeys(function ($m) use ($currentYear) {
        $date = Carbon::createFromDate($currentYear, $m, 1);
        $monthAbbr = $date->format('M'); // Jan, Feb, etc.
        $groupKey = $date->format('Y-m');
        return [$monthAbbr => ['groupKey' => $groupKey, 'date' => $date]];
    });

    // Group appointments by year-month (Y-m)
    $groupedAppointments = $appointments->groupBy(function ($appt) {
        return Carbon::parse($appt->appointment_date)->format('Y-m');
    });

    // Map months to their appointments or empty collection
    $allMonths = $months->map(function ($data, $monthAbbr) use ($groupedAppointments) {
        return $groupedAppointments->get($data['groupKey'], collect());
    });
@endphp

<div class="container mt-4">

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6 class="fw-bold">Total Appointments</h6>
                <h3 class="fw-bold text-primary">{{ $appointments->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6 class="fw-bold">Approved</h6>
                <h3 class="fw-bold text-success">{{ $appointments->where('status', 'approved')->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6 class="fw-bold">Pending</h6>
                <h3 class="fw-bold text-warning">{{ $appointments->where('status', 'pending')->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6 class="fw-bold">Cancelled</h6>
                <h3 class="fw-bold text-danger">{{ $appointments->where('status', 'cancelled')->count() }}</h3>
            </div>
        </div>
    </div>



    {{-- Monthly Tabs --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="fw-bold mb-0">List of Appointments</h5>
        </div>

        <div class="card-body">
            {{-- Tabs Navigation --}}
            <ul class="nav nav-tabs d-flex justify-content-between flex-wrap" id="monthTabs" role="tablist" style="gap:0.5rem;">
                @foreach($allMonths as $month => $monthAppointments)
                    @php
                        $isCurrent = ($month === Carbon::now()->format('M'));
                    @endphp
                    <li class="nav-item flex-fill text-center" role="presentation" style="min-width: 60px;">
                        <button class="nav-link @if($isCurrent) active @endif text-truncate"
                                id="tab-{{ $loop->index }}"
                                data-bs-toggle="tab"
                                data-bs-target="#month-{{ $loop->index }}"
                                type="button"
                                role="tab"
                                aria-controls="month-{{ $loop->index }}"
                                aria-selected="{{ $isCurrent ? 'true' : 'false' }}"
                                title="{{ $month }}">
                            {{ $month }}
                            @if($monthAppointments->count() > 0)
                                <span class="badge bg-secondary ms-1">{{ $monthAppointments->count() }}</span>
                            @endif
                        </button>
                    </li>
                @endforeach
            </ul>

            {{-- Tabs Content --}}
            <div class="tab-content mt-3" id="monthTabsContent">
                @foreach($allMonths as $month => $monthAppointments)
                    @php
                        $isCurrent = ($month === Carbon::now()->format('M'));
                    @endphp
                    <div class="tab-pane fade @if($isCurrent) show active @endif"
                         id="month-{{ $loop->index }}"
                         role="tabpanel"
                         aria-labelledby="tab-{{ $loop->index }}">

                        @if($monthAppointments->isEmpty())
                            <p class="text-muted">No appointments for {{ $month }}.</p>
                        @else
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthAppointments as $appt)
                                        <tr>
                                            <td>{{ $appt->patient->user->name ?? ($appt->patient->first_name ?? 'N/A') }}</td>
                                            <td>{{ $appt->doctor->user->name ?? 'N/A' }}</td>
                                            <td>{{ Carbon::parse($appt->appointment_date)->format('M d, Y') }}</td>
                                            <td>{{ Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                                            <td>
                                                <span class="badge
                                                    @if($appt->status === 'approved') bg-success
                                                    @elseif($appt->status === 'pending') bg-warning text-dark
                                                    @elseif($appt->status === 'cancelled') bg-danger
                                                    @else bg-secondary
                                                    @endif
                                                ">
                                                    {{ ucfirst($appt->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
