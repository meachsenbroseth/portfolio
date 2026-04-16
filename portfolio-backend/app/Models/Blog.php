<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'meta_title',
        'meta_description',
        'tags',
        'status',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',           // JSON → array
        'published_at' => 'datetime', // timestamp → Carbon
    ];
}
