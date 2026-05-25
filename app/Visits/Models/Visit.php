<?php

namespace App\Visits\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visitable_type',
        'visitable_id',
        'user_id',
        'visitor_type',
        'visitor_hash',
        'interval',
        'interval_key',
        'data',
        'visited_at',
    ];

    protected $casts = [
        'data' => 'array',
        'visited_at' => 'datetime',
    ];

    public function visitable()
    {
        return $this->morphTo();
    }
}
