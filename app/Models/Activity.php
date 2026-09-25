<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $guarded = [];
    protected $casts = ['occurred_at' => 'datetime'];
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
}
