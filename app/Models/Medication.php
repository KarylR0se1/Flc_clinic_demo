<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMedication
 */
class Medication extends Model
{
    protected $fillable = [
        'medical_record_id','name','dosage','frequency','route','start_date','end_date'
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
