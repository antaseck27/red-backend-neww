<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'address',
    'email',
    'phone',
    'price_per_night',
    'currency',
    'image',
];


    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image
        ? Storage::disk('public')->url($this->image)
        : null;
    }
}
