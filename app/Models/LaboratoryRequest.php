<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLaboratoryRequest
 */
class LaboratoryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'medical_record_id',
        'test_type',
        'requested_by',
        'result_file',
        'status',
        'patient_name',
        'address',
        'request_date',
        'age_sex',
        'diagnosis',
        'chemistry',
        'hematology',
        'serology',
        'clinical_microscopy',
        'parasitology',
        'microbiology',
        'others',
        'requesting_physician',
    ];

    protected $casts = [
        'chemistry' => 'array',
        'hematology' => 'array',
        'serology' => 'array',
        'clinical_microscopy' => 'array',
        'parasitology' => 'array',
        'microbiology' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function medicalRecord()
{
    return $this->belongsTo(MedicalRecord::class);
}

}
