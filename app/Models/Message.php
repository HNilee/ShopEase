<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
protected $fillable = [
    'order_id', 'customer_id', 'chat_group_id', 'sender_id', 'body', 'image_path', 'is_read', 'reply_to_id',
    ];

    // Tambahkan relasi ini di bagian bawah
    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
