<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = ['member1', 'member2'];

    public function member1()
    {
        return $this->belongsTo(User::class);
    }

    public function member2()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->hasMany(Message::class);
    }
}
