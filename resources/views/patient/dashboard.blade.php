@extends('layouts.app')

@section('content')
<style>
body {
    background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);
}

.main-content {
    padding-top: 2rem;
    padding-bottom: 2rem;
}

/* Page Heading */
.main-content h2 {
    font-weight: 800;
    color: #1e40af;
    margin-bottom: 2rem;
    font-size: 2rem;
}

/* Doctors Grid */
.doctor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    justify-items: center;
}

/* Doctor Card */
.doctor-card {
    position: relative;
    width: 100%;
    max-width: 400px;
    height: 280px;
    border-radius: 20px 20px 8px 8px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.35s ease;
    background: linear-gradient(135deg, #ffffffdd, #ffffffbb);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
}

.doctor-card:hover {
    transform: translateY(-6px) scale(1.03);
    box-shadow: 0 12px 30px rgba(0,0,0,0.45);
}

/* Glass Overlay */
.glass-overlay {
    position: relative;
    height: 100%;
    width: 100%;
    backdrop-filter: blur(12px);
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 1.5rem;
    border-radius: 20px 20px 8px 8px;
    transition: all 0.3s ease;
}

/* Doctor Name */
.doctor-name {
    font-size: 1.7rem;
    font-weight: 800;
    color: #080808ff;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Specialization Badge */
.doctor-specialization {
    font-size: 1rem;
    font-weight: 600;
    background: linear-gradient(90deg, #ffcc00, #ff9900);
    color: #1a1a1a;
    padding: 5px 14px;
    border-radius: 25px;
    margin-bottom: 1rem;
    letter-spacing: 0.5px;
}

/* Schedule Text */
.doctor-schedule .schedule-line {
    font-size: 1.10rem;
    font-weight: 600;
    color: #0f4bf0ff;
    margin-bottom: 6px;
}

/* No schedule fallback */
.doctor-schedule .fst-italic {
    color: #555;
    font-size: 1rem;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .doctor-card { height: 240px; }
    .doctor-name { font-size: 1.4rem; }
    .doctor-specialization { font-size: 0.9rem; padding: 4px 12px; }
    .doctor-schedule .schedule-line { font-size: 0.9rem; }
}
</style>

<main class="main-content">
    <h2 class="text-center">Our Doctors</h2>

    <div class="doctor-grid">
        @foreach($doctors as $doctor)
        <a href="{{ route('appointments.create', ['doctor' => $doctor->id]) }}" class="text-decoration-none w-100">
            <div class="doctor-card">
                <div class="glass-overlay">
                    <h5 class="doctor-name">{{ $doctor->user->name }}</h5>
                    <p class="doctor-specialization">{{ $doctor->specialization }}</p>

                    <div class="doctor-schedule mt-2">
                        @if($doctor->schedules->isNotEmpty())
                            @php
                                $daysOrder = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                                $dayIndex = array_flip($daysOrder);

                                $grouped = [];
                                foreach ($doctor->schedules as $s) {
                                    $day = ucfirst(strtolower($s->day));
                                    $timeRange = date('g:i a', strtotime($s->start_time)) . ' - ' . date('g:i a', strtotime($s->end_time));
                                    $grouped[$timeRange][] = $day;
                                }

                                $output = [];
                                foreach ($grouped as $timeRange => $days) {
                                    $days = array_values(array_unique($days));
                                    usort($days, fn($a, $b) => $dayIndex[$a] <=> $dayIndex[$b]);

                                    $ranges = [];
                                    $start = $prev = $days[0];
                                    for ($i = 1; $i < count($days); $i++) {
                                        if ($dayIndex[$days[$i]] === $dayIndex[$prev] + 1) {
                                            $prev = $days[$i];
                                        } else {
                                            $ranges[] = ($start === $prev) ? $start : "{$start}–{$prev}";
                                            $start = $prev = $days[$i];
                                        }
                                    }
                                    $ranges[] = ($start === $prev) ? $start : "{$start}–{$prev}";
                                    $output[] = implode(', ', $ranges) . ': ' . $timeRange;
                                }
                            @endphp

                            @foreach($output as $line)
                                <p class="schedule-line">{{ $line }}</p>
                            @endforeach
                        @else
                            <p class="schedule-line fst-italic">No schedule set</p>
                        @endif
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</main>
@endsection
