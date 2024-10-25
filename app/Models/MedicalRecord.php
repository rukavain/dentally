<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'x_rays',
        'background',
        'contract',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
