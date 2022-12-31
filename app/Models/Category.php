<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasScope;
use App\Traits\HasSlug;

class Category extends Model
{
    use HasFactory, HasSlug, HasScope;

    protected $fillable = ['name', 'image', 'slug'];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => asset('storage/categories/' . $image),
        );
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}