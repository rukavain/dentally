<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table = 'staff';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'gender',
        'fb_name',
        'branch_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

