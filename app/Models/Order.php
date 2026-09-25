<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $guarded = [];
    protected $casts = ['placed_at' => 'datetime', 'total' => 'decimal:2'];
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
}
