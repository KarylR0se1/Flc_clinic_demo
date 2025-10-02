<?php

// app/Models/Notification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperNotification
 */
class Notification extends Model
{
    protected $fillable = ['patient_id', 'title', 'message', 'is_read'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
