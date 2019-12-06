<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = [
        'amount', 'user_id', 'ad_id',
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
