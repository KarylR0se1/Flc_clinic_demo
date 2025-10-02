<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAllergy
 */
class Allergy extends Model
{
    protected $fillable = ['patient_id','substance','reaction','severity'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
