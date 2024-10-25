<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'email',
        'password',
        'next_visit',
        'fb_name',
        'phone_number',
        'gender',
        'branch_id',
        'has_hmo',
        'hmo_company',
        'hmo_number',
        'hmo_type',
        'patient_type',
    ];

    // Define a scope to get only non-archived patients
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    // Define a scope to get only archived patients
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function record()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }

    public function branch() 
    {
        return $this->belongsTo(Branch::class);
    }

    
    //Attribute
    public function getFirstInitialAttribute()
    {
        return substr($this->first_name, 0, 1);
    }

    public function getFirstNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getLastNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
    public function getGenderAttribute($value)
    {
        return ucwords(strtolower($value));
    }
}
