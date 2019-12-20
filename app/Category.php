<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['tag'];
    public function ad()
    {
        return $this->hasMany(Ad::class);
    }
}
