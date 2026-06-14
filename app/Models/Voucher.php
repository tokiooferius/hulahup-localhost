<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'canteen_id',
        'code',
        'description',
        'discount_percentage',
        'discount_amount',
        'valid_from',
        'valid_to',
        'max_uses',
        'times_used',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    /**
     * Get the canteen this voucher belongs to
     */
    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }
}
