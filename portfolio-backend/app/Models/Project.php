<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'category',
        'date',
        'image',
        'github_link',
        'technologies',
        'status',
    ];

    protected $casts = [
        'technologies' => 'array',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/'.$this->image)
            : null;
    }
}
