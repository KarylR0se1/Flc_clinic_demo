<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMedicalRecord
 */
class MedicalRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_id',
        'visit_date',
        'reason_of_visit',
        'history_of_present_illness',
        'examination',
        'assessment',
        'treatment_plan',
        'current_medications',
        'progress_notes',
        'lab_results',
        'diagnostic_results',
        'bp',
        'hr',
        'rr',
        'temp',
        'oxygen_saturation',
        'weight',
        'height',
        // Family Medical History
        'family_hypertension',
        'family_diabetes',
        'family_heart_disease',
        'family_cancer',
        'family_tb',
        // Immunization Records
        'childhood_vaccines',
        'adult_vaccines',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function laboratoryRequests()
    {
        return $this->hasMany(LaboratoryRequest::class, 'medical_record_id');
    }
}
