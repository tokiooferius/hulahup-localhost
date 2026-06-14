<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canteen extends Model
{
    use HasFactory;

    protected $fillable = [
        'ibu_kantin_id',
        'name',
        'description',
        'location',
        'image',
        'logo_url',
        'status',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    /**
     * Get the Ibu Kantin (User) who owns this canteen
     */
    public function ibuKantin()
    {
        return $this->belongsTo(User::class, 'ibu_kantin_id');
    }

    /**
     * Get all menu items for this canteen
     */
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get all vouchers for this canteen
     */
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    /**
     * Get all orders for this canteen
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all payment details for this canteen
     */
    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    /**
     * Get all settlements for this canteen
     */
    public function settlements()
    {
        return $this->hasMany(CanteenSettlement::class);
    }
}
