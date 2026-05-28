<?php

namespace App\Models\Demo;

use Database\Factories\Demo\SearchPostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchPost extends Model
{
    /** @use HasFactory<SearchPostFactory> */
    use HasFactory;

    protected $table = 'search_demo_posts';

    protected $fillable = [
        'title',
        'subtitle',
        'excerpt',
        'body',
        'cover_image',
        'slug',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected static function newFactory(): SearchPostFactory
    {
        return SearchPostFactory::new();
    }
}
