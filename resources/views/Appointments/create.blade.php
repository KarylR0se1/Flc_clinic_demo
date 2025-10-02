@extends('layouts.app')

@section('content')
<style>
  body { background: linear-gradient(135deg, #e6f0ff 0%, #3594edff 100%); }
  #calendar { min-height: 380px; max-height: 600px; }
  @media (max-width: 768px) { #calendar { min-height: 300px; } }

  /* Sundays marked as Day Off */
  .fc-day-sun {
      background-color: #eee34d4e !important;
      color: #eee34d4e !important;
      pointer-events: none;
      opacity: 0.7;
  }
</style>

<div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 w-full max-w-full xl:max-w-6xl mx-auto">
    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-4 sm:mb-6">Book Your Appointment</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-3 py-2 rounded mb-4 text-center text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="bg-red-100 text-red-800 px-3 py-2 rounded mb-4 text-center text-sm sm:text-base">
            {{ session('error') }}
        </div>
    @endif

    <!-- Legend -->
    <div class="mt-4 sm:mt-6 flex flex-wrap gap-3 sm:gap-4 justify-center text-xs sm:text-sm md:text-base" id="calendar-legend">
        <div class="flex items-center gap-1 sm:gap-2">
            <span class="w-4 h-4 sm:w-5 sm:h-5 bg-white border border-gray-300 rounded"></span>
            <span>Available Day</span>
        </div>
        <div class="flex items-center gap-1 sm:gap-2">
            <span class="w-4 h-4 sm:w-5 sm:h-5 bg-gray-300 rounded"></span>
            <span>Not Available</span>
        </div>
        <div class="flex items-center gap-1 sm:gap-2">
            <span class="w-4 h-4 sm:w-5 sm:h-5 bg-blue-700 rounded"></span>
            <span>Selected Day</span>
        </div>
        <div class="flex items-center gap-1 sm:gap-2">
            <span class="w-4 h-4 sm:w-5 sm:h-5 rounded" style="background-color:#eee34d4e; border:1px solid #eee34d4e;"></span>
            <span>Sunday (Day Off)</span>
        </div>
    </div>

    <form method="POST" action="{{ route('appointments.store') }}" class="mt-6">
        @csrf
        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
        <input type="hidden" id="appointment_date" name="appointment_date" required>
        <input type="hidden" id="appointment_time" name="appointment_time" required>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Calendar -->
            <div id="calendar" class="w-full border border-gray-200 rounded-lg shadow-sm overflow-hidden"></div>

            <!-- Time Slots -->
            <div>
                <h3 class="text-base sm:text-lg font-semibold mb-2 sm:mb-3 text-center lg:text-left">Available Time Slots</h3>
                <div id="time-slots" 
                     class="grid grid-cols-2 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 gap-2 sm:gap-3 text-xs sm:text-sm md:text-base">
                </div>
            </div>
        </div>

        <button id="submit-btn" type="submit"
            class="mt-4 sm:mt-6 bg-blue-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded hover:bg-blue-700 transition w-full font-semibold text-base sm:text-lg disabled:opacity-50 disabled:cursor-not-allowed"
            disabled>
            Confirm Appointment
        </button>
    </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.js"></script>

<script>
    const schedules = @json($schedules);
    const bookedSlots = @json($bookedSlots ?? []).map(time => to12Hour(time));
    let availableDays = Object.keys(schedules);

    // Force Sunday OFF
    availableDays = availableDays.filter(day => day !== "Sunday");

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
            dateClick: function (info) {
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
                selectedDateEl.style.backgroundColor = '#1e40af';
                selectedDateEl.style.color = '#fff';

                loadTimeSlots(info.dateStr);
            }
        });

        calendar.render();

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

    function generateSlots(startTime = "08:00", endTime = "16:00") {
        const slots = [];
        let current = new Date(`1970-01-01T${startTime}`);
        const end = new Date(`1970-01-01T${endTime}`);

        // Create up to 25 slots per day, skipping lunch 12-1
        while (current < end && slots.length < 25) {
            const h = current.getHours();
            if (h === 12) { current.setHours(13,0); continue; } // skip lunch
            const startStr = current.toTimeString().slice(0,5);
            current.setHours(current.getHours() + 1); // 1 hour per slot
            const endStr = current.toTimeString().slice(0,5);
            slots.push({ start: startStr, end: endStr });
        }
        return slots;
    }

    function createTimeButton(label, time12) {
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = label;

        if (bookedSlots.includes(time12)) {
            button.disabled = true;
            button.className = "time-button border border-gray-300 px-2 sm:px-3 py-1 sm:py-2 rounded bg-gray-200 text-gray-500 cursor-not-allowed text-xs sm:text-sm";
        } else {
            button.className = "time-button border border-gray-300 px-2 sm:px-3 py-1 sm:py-2 rounded hover:bg-orange-100 transition text-xs sm:text-sm";
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
        for (let i=0; i<30; i++) {
            const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
            if (availableDays.includes(dayName)) return date.toISOString().split("T")[0];
            date.setDate(date.getDate()+1);
        }
        return startDate;
    }
</script>
@endsection
