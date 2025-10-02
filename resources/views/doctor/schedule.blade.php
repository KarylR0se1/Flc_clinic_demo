@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
</style>

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white text-center py-3 rounded-top">
            <h2 class="mb-0 fw-bold">Doctor Schedule</h2>
        </div>

        <div class="card-body p-4">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('doctor.schedule.update') }}" method="POST">
                @csrf

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th class="fw-semibold">Day</th>
                                <th class="fw-semibold">Start Time</th>
                                <th class="fw-semibold">End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                            @endphp
                            @foreach($days as $day)
                                @php
                                    $schedule = $schedules->firstWhere('available_day', $day);
                                @endphp
                                <tr>
                                    <td class="fw-medium">{{ $day }}</td>
                                    <td>
                                        <input type="time" 
                                               name="days[{{ $day }}][start_time]"
                                               value="{{ $schedule->start_time ?? '' }}"
                                               class="form-control text-center">
                                    </td>
                                    <td>
                                        <input type="time" 
                                               name="days[{{ $day }}][end_time]"
                                               value="{{ $schedule->end_time ?? '' }}"
                                               class="form-control text-center">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm fw-semibold">
                        Save Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
