<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'branch_id',
        'schedule_id',
        'proc_id',
        'preferred_time',
        'appointment_date',
        'status',
        'pending',
        'is_online',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function dentistSchedule()
    {
        return $this->belongsTo(DentistSchedule::class);
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class, 'proc_id');
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}
