<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Reminder</title>
</head>
<body>
    {{-- resources/views/emails/appointment_reminder.blade.php --}}
<p>Hello {{ $appointment->patient->first_name }},</p>

<p>This is a reminder for your upcoming appointment:</p>

<ul>
    <li><strong>Doctor:</strong> Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</li>
    <li><strong>Date:</strong> {{ $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : 'N/A' }}</li>
    <li><strong>Time:</strong> {{ $appointment->appointment_time ?? 'N/A' }}</li>
</ul>


@if($customMessage)
    <p>{{ $customMessage }}</p>
@endif

<p>Thank you!</p>

</body>
</html>
