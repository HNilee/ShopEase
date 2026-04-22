<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'age',
        'email',
        'ktp_path',
        'purpose',
        'security_confidence',
        'agree_to_sop',
        'agree_to_terms',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'agree_to_sop' => 'boolean',
        'agree_to_terms' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}