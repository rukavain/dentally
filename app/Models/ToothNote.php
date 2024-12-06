<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToothNote extends Model
{
    protected $fillable = [
        'tooth_record_id',
        'note_text'
    ];
    public function toothRecord(): BelongsTo
    {
        return $this->belongsTo(ToothRecord::class);
    }
}
