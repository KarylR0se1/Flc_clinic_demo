<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $upcomingReminderCount = 0;

            if (!Auth::check()) {
                $view->with('upcomingReminderCount', $upcomingReminderCount);
                return;
            }

            $tomorrow = now()->addDay()->format('Y-m-d');

            $user = Auth::user();

            if ($user->role === 'patient' && $user->patient) {
                $upcomingReminderCount = Appointment::where('patient_id', $user->patient->id)
                    ->where('status', 'accepted')
                    ->where('appointment_date', $tomorrow)
                    ->count();
            }

            elseif ($user->role === 'doctor' && $user->doctor) {
                $upcomingReminderCount = Appointment::where('doctor_id', $user->doctor->id)
                    ->where('status', 'accepted')
                    ->where('appointment_date', $tomorrow)
                    ->count();
            }

            elseif ($user->role === 'admin') {
                $upcomingReminderCount = Appointment::where('status', 'accepted')
                    ->where('appointment_date', $tomorrow)
                    ->count();
            }

            $view->with('upcomingReminderCount', $upcomingReminderCount);
        });
    }
    
}
