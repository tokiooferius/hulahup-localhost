<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanteenSettlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'canteen_id',
        'payment_detail_id',
        'amount_received',
        'status',
        'settlement_date',
        'bank_account',
    ];

    protected $casts = [
        'amount_received' => 'decimal:2',
        'settlement_date' => 'datetime',
    ];

    /**
     * Get the canteen this settlement belongs to
     */
    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }

    /**
     * Get the payment detail this settlement is from
     */
    public function paymentDetail()
    {
        return $this->belongsTo(PaymentDetail::class);
    }
}
