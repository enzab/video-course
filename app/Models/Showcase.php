<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\HasScope;

class Showcase extends Model
{
    use HasFactory, HasScope;

    protected $fillable = [
        'user_id', 'course_id', 'title', 'cover', 'description'
    ];

    protected function cover(): Attribute
    {
        return Attribute::make(
            get: fn($cover) => asset('storage/showcases/' . $cover),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}