<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperImmunization
 */
class Immunization extends Model
{
    protected $fillable = ['patient_id','vaccine_name','date_administered','administered_by'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
