<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'paid_amount',
        'payment_date',
        'payment_method',
        'remarks',
        'payment_proof',
        'status',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
