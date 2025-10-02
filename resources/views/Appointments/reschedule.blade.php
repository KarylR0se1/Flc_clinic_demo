@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%);}
</style>
<div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-full xl:max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold text-center mb-6">Reschedule Appointment</h2>

    {{-- Original Appointment --}}
    <div class="mb-4 text-center text-lg text-gray-700">
        Original Appointment Date: 
        <span class="font-semibold">
            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}
        </span>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Legend -->
    <div class="mt-6 flex flex-wrap gap-4 justify-center mb-4" id="calendar-legend">
        <div class="flex items-center gap-2">
            <span class="w-5 h-5 bg-white border border-gray-300 rounded"></span>
            <span>Available Day</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-5 h-5 bg-gray-500 rounded"></span>
            <span>Not Available</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-5 h-5 bg-blue-700 rounded"></span>
            <span>Selected Day</span>
        </div>
    </div>

    <form method="POST" action="{{ route('appointments.updateReschedule', $appointment->id) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" id="appointment_date" name="appointment_date" required>
        <input type="hidden" id="appointment_time" name="appointment_time" required>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Calendar -->
            <div id="calendar"></div>

            <!-- Time Slots -->
            <div>
                <h3 class="text-lg font-semibold mb-3">Available Time Slots</h3>
                <div id="time-slots" class="grid grid-cols-2 sm:grid-cols-3 gap-3"></div>
            </div>
        </div>

        <button id="submit-btn" type="submit"
            class="mt-6 bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition w-full font-semibold text-lg"
            disabled>
            Save Changes →
        </button>
    </form>
</div>

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.js"></script>

<script>
const schedules = @json($schedules);
const bookedSlots = @json($bookedSlots ?? []).map(time => to12Hour(time));
const availableDays = Object.keys(schedules);
let selectedDateEl = null;

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const today = new Date().toISOString().split("T")[0];

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        validRange: { start: today },
        selectable: true,
        dayCellClassNames: function(arg) {
            const dayName = arg.date.toLocaleDateString('en-US', { weekday: 'long' });
            if (!availableDays.includes(dayName)) return ['fc-day-disabled'];
        },
        dateClick: function(info) {
            const dayName = new Date(info.dateStr).toLocaleDateString('en-US', { weekday: 'long' });
            if (!availableDays.includes(dayName)) {
                alert('This date is not available for appointments.');
                return;
            }

            if (selectedDateEl) {
                selectedDateEl.style.backgroundColor = '';
                selectedDateEl.style.color = '';
            }
            selectedDateEl = info.dayEl;
            selectedDateEl.style.backgroundColor = '#1e40af'; // selected dark blue
            selectedDateEl.style.color = '#fff';

            loadTimeSlots(info.dateStr);
        }
    });

    calendar.render();

    // Auto-select today or next available
    let autoDate = today;
    const todayName = new Date(today).toLocaleDateString('en-US', { weekday: 'long' });
    if (!availableDays.includes(todayName)) autoDate = findNextAvailableDay(today);
    loadTimeSlots(autoDate);
});

function loadTimeSlots(dateStr) {
    const dayName = new Date(dateStr).toLocaleDateString('en-US', { weekday: 'long' });
    const schedule = schedules[dayName];
    const timeContainer = document.getElementById('time-slots');
    const hiddenDate = document.getElementById('appointment_date');
    const hiddenTime = document.getElementById('appointment_time');
    const submitBtn = document.getElementById('submit-btn');

    timeContainer.innerHTML = '';
    hiddenDate.value = '';
    hiddenTime.value = '';
    submitBtn.disabled = true;

    if (!schedule) return;

    hiddenDate.value = dateStr;
    const slots = generateSlots(schedule.start_time, schedule.end_time);

    if (slots.length === 0) {
        timeContainer.innerHTML = '<p class="text-gray-500">No available slots for this day.</p>';
    } else {
        slots.forEach(slot => {
            const start12 = to12Hour(slot.start);
            const end12 = to12Hour(slot.end);
            const btn = createTimeButton(`${start12} - ${end12}`, start12);
            timeContainer.appendChild(btn);
        });
    }
}

function generateSlots(startTime, endTime, intervalMinutes = 60) {
    const slots = [];
    let current = new Date(`1970-01-01T${startTime}`);
    const end = new Date(`1970-01-01T${endTime}`);

    while (current < end) {
        const slotStart = new Date(current);
        const slotEnd = new Date(current);
        slotEnd.setMinutes(slotEnd.getMinutes() + intervalMinutes);
        if (slotEnd > end) break;

        const startStr = slotStart.toTimeString().slice(0,5);
        const endStr = slotEnd.toTimeString().slice(0,5);
        slots.push({ start: startStr, end: endStr });

        current.setMinutes(current.getMinutes() + intervalMinutes);
    }
    return slots;
}

function createTimeButton(label, time12) {
    const button = document.createElement('button');
    button.type = 'button';
    button.textContent = label;

    if (bookedSlots.includes(time12)) {
        button.disabled = true;
        button.className = "time-button border border-gray-300 px-3 py-2 rounded bg-gray-200 text-gray-500 cursor-not-allowed";
    } else {
        button.className = "time-button border border-gray-300 px-3 py-2 rounded hover:bg-orange-100 transition";
        button.addEventListener('click', function () {
            document.querySelectorAll('.time-button').forEach(btn => {
                btn.classList.remove('bg-orange-500','text-white');
            });
            this.classList.add('bg-orange-500','text-white');
            document.getElementById('appointment_time').value = this.dataset.time;
            document.getElementById('submit-btn').disabled = false;
        });
    }

    button.dataset.time = time12;
    return button;
}

function to12Hour(timeStr) {
    const [hour, minute] = timeStr.split(':');
    const h = parseInt(hour);
    const ampm = h >= 12 ? 'PM' : 'AM';
    const hour12 = h % 12 === 0 ? 12 : h % 12;
    return hour12 + ':' + minute + ' ' + ampm;
}

function findNextAvailableDay(startDate) {
    let date = new Date(startDate);
    for (let i = 0; i < 30; i++) {
        const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
        if (availableDays.includes(dayName)) return date.toISOString().split("T")[0];
        date.setDate(date.getDate() + 1);
    }
    return startDate;
}
</script>

<style>
.fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 600; }
.fc .fc-daygrid-day.fc-day-today { background-color: #fef9c3 !important; }
.fc-day-disabled { background-color: #6b7280 !important; color: #f3f4f6 !important; pointer-events: none; }
.time-button { transition: all 0.2s ease-in-out; user-select: none; }
</style>
@endsection
