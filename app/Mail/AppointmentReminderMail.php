<?php

// app/Mail/AppointmentReminderMail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;

class AppointmentReminderMail extends Mailable
{
    use  SerializesModels;

    public $appointment;
    public $customMessage;

    public function __construct(Appointment $appointment, $customMessage = null)
    {
        $this->appointment = $appointment;
        $this->customMessage = $customMessage;
    }

    public function build()
    {
        return $this->subject('Appointment Reminder')
                    ->view('emails.appointment_reminder');
    }
}
