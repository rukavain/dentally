<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
        'patient_id',
        'image_path',
        'image_type'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
