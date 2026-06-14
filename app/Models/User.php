<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'nim',
        'role',
        'phone',
        'address',
        'avatar',
        'balance',
        'canteen_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the canteen if user is ibu_kantin
     */
    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }

    /**
     * Get canteens owned by this user (if ibu_kantin)
     */
    public function ownedCanteens()
    {
        return $this->hasMany(Canteen::class, 'ibu_kantin_id');
    }

    /**
     * Get all payments made by this user
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is ibu_kantin
     */
    public function isIbuKantin(): bool
    {
        return $this->role === 'ibu_kantin';
    }

    /**
     * Check if user is regular user/customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'user';
    }
}
