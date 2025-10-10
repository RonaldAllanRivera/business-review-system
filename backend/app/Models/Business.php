<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'rating',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
