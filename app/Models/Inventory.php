<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'item_name',
        'branch_id',
        'quantity',
        'minimum_quantity',
        'serial_number',
        'cost_per_item',
        'availability',
        'total_value',
        'notes',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
