<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    use HasFactory;

    protected $table = 'dentists';

    protected $fillable = [
        'dentist_first_name',
        'dentist_last_name',
        'dentist_birth_date',
        'dentist_email',
        'dentist_gender',
        'dentist_phone_number',
        'password',
        'dentist_specialization',
        'branch_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function dentistSchedule()
    {
        return $this->hasMany(DentistSchedule::class);
    }
    
    public function getFirstInitialAttribute()
    {
        return substr($this->dentist_first_name, 0, 1);
    }
    
    public function getDentistFirstNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
    public function getDentistLastNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
    public function getDentistSpecializationAttribute($value)
    {
        return ucwords(strtolower($value));
    }
    // public function getBranchAttribute($value)
    // {
    //     return ucwords(strtolower($value));
    // }
}
