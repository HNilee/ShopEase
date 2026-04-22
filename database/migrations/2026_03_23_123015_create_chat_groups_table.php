<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        // Relasi agar Grup tahu siapa saja membernya
        return $this->belongsToMany(User::class, 'chat_group_user');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}