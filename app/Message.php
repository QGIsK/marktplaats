<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message', 'chat_room', 'user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function chat_room()
    {
        return $this->belongsTo(ChatRoom::class);
    }
}
