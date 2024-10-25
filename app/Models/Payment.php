<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'amount_due',
        'total_paid',
        'balance_remaining',
        'status',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function paymentHistory()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function temporaryPayment()
    {
        return $this->hasMany(TemporaryPayment::class);
    }
}
