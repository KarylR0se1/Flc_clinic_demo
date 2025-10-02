<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function availableSlots(Doctor $doctor, Request $request)
{
    $date = $request->query('date');
    if (!$date) {
        return response()->json(['slots' => []]);
    }

    $dayOfWeekName = \Carbon\Carbon::parse($date)->format('l');

    $schedules = $doctor->schedules()->where('day_of_week', $dayOfWeekName)->get();

    $bookedSlots = Appointment::where('doctor_id', $doctor->id)
        ->where('appointment_date', $date)
        ->pluck('time')
        ->toArray();

    $slots = [];

    foreach ($schedules as $schedule) {
        $start = strtotime($schedule->start_time);
        $end = strtotime($schedule->end_time);

        while ($start < $end) {
            $slotStart = date('H:i', $start);
            $slotEnd = date('H:i', min($start + 1800, $end)); // 30-min slots

            if (!in_array($slotStart, $bookedSlots)) {
                $slots[] = ['start_time' => $slotStart, 'end_time' => $slotEnd];
            }
            $start += 1800;
        }
    }

    return response()->json(['slots' => $slots]);
}

}
