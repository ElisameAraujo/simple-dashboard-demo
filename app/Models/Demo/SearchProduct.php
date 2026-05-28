<?php

namespace App\Models\Demo;

use Database\Factories\Demo\SearchProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchProduct extends Model
{
    /** @use HasFactory<SearchProductFactory> */
    use HasFactory;

    protected $table = 'search_demo_products';

    protected $fillable = [
        'name',
        'description',
        'image',
        'slug',
        'price',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'published_at' => 'datetime',
        ];
    }

    protected static function newFactory(): SearchProductFactory
    {
        return SearchProductFactory::new();
    }
}
