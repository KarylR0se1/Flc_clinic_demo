<?php

namespace App\Models;
/**
 * @property \App\Models\User $user
 * @mixin IdeHelperAppointment
 */
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DoctorSchedule;

class Appointment extends Model
{
    use HasFactory;

    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED  = 'canceled';

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'appointment_time',
        'status',
    ];
     protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i', // optional if you want Carbon for time too
    ];

    /**
     * Relationship: Appointment belongs to a Doctor
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id','id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    // In Appointment.php
public function getPatientNameAttribute()
{
    return $this->patient?->user?->name ?? $this->patient?->first_name.' '.$this->patient?->last_name ?? 'N/A';
}
public function medicalRecord()
{
    return $this->hasOne(MedicalRecord::class);
}
public function schedule()
{
    return $this->belongsTo(\App\Models\DoctorSchedule::class, 'schedule_id');
}
public function laboratoryRequests()
{
    return $this->hasMany(LaboratoryRequest::class);
}

}
