<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Patient
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property \Carbon\Carbon $birthdate
 * @property string $sex
 * @property string $address
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MedicalRecord[] $medicalRecords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Allergy[] $allergies
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Immunization[] $immunizations
 * @property-read string $full_name
 * @mixin IdeHelperPatient
 */
class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'sex',
        'address',
        'user_id',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    // ✅ Accessor instead of method
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
