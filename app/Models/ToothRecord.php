<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ToothRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'tooth_number',
        'status'
    ];
    protected $casts = [
        'tooth_number' => 'integer'
    ];
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    public function note(): HasOne
    {
        return $this->hasOne(ToothNote::class);
    }
}
