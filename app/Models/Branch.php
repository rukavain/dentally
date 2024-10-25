<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';


    protected $fillable = [
        'branch_loc',
    ];

    public function dentists()
    {
        return $this->hasMany(Dentist::class, 'branch_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'branch_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
    
    public function getBranchLocAttribute($value)
    {
        return ucwords(strtolower($value));
    }
}
