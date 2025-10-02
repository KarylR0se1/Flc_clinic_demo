<?php
// app/Http/Controllers/Admin/PatientAppointmentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Carbon;

class PatientAppointmentController extends Controller
{
    public function index()
    {
        // Only allow admins
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Get all doctors with their appointments and the related patient
        $doctors = Doctor::with(['appointments.patient'])->get();

        // Debug: check what patients are loaded
        // foreach ($doctors as $doctor) {
        //     foreach ($doctor->appointments as $appointment) {
        //         dd($appointment->patient);
        //     }
        // }

        // Quick stats for dashboard
        $pendingCount        = Appointment::where('status', 'pending')->count();
        $rescheduledCount    = Appointment::where('status', 'rescheduled')->count();
        $todayCount          = Appointment::whereDate('appointment_date', Carbon::today())->count();
        $unableToAttendCount = Appointment::where('status', 'unable_to_attend')->count();

        return view('admin.appointments.index', compact(
            'doctors',
            'pendingCount',
            'rescheduledCount',
            'todayCount',
            'unableToAttendCount'
        ));
    }

    public function updateStatus($id, $status)
    {
        $appointment = Appointment::findOrFail($id);

        $validStatuses = [
            Appointment::STATUS_PENDING,
            Appointment::STATUS_APPROVED,
            Appointment::STATUS_REJECTED,
            Appointment::STATUS_COMPLETED,
            Appointment::STATUS_CANCELED,
        ];

        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $appointment->status = $status;
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment status updated.');
    }
}
