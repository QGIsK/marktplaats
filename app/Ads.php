<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ads extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'user_id', 'description', 'featuredAt', 'image',
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
