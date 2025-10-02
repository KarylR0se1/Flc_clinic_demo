<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification
{
    use Queueable;

    protected $appointment;
    protected $message;

    public function __construct($appointment, $message)
    {
        $this->appointment = $appointment;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // store in notifications table
    }

    public function toDatabase($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'message'        => $this->message,
        ];
    }
}
