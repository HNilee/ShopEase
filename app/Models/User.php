<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'role',
        'password',
        'profile_pic',
        'is_blocked',
        'block_reason',
        'ip_address',
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

    public function sellerApplication()
    {
        return $this->hasOne(SellerApplication::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function productApprovals()
    {
        return $this->hasMany(ProductApproval::class, 'seller_id');
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSeller()
    {
        return $this->role === 'seller';
    }

    public function isBuyer()
    {
        return $this->role === 'buyer';
    }

    public function canManageUsers()
    {
        return $this->isOwner() || $this->isAdmin();
    }

    public function canManageProducts()
    {
        return $this->isOwner() || $this->isAdmin() || $this->isSeller();
    }
}
