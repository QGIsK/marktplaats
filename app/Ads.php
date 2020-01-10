<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ads extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'user_id',
        'description',
        'featuredAt',
        'image',
        'lat',
        'long',
        'categories',
        'postalCode'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
