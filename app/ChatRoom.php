<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = ['members'];
    public function message()
    {
        return $this->hasMany(Message::class);
    }
    protected $casts = [
        'shifts' => 'array'
    ];
}
