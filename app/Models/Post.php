<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "posts";

    protected $fillable = [
        'title',
        'body',
        'cover_image',
        'is_pinned',
        'tags',
    ];
    
    public function tags()
    {
        return $this->belongsToMany(related: Tag::class);
    }
    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }
}
