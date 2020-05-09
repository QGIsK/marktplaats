<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = ['member1', 'member2'];
    public function message()
    {
        return $this->hasMany(Message::class);
    }
}
